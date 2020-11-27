<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class TeamController extends Controller
{

    private function processImage($file, $type, $destination)
    {
        $NEW_FILE_NAME = "";
        $NEW_FILE_NAME = $type . "-" . time() . "-" . $file;
        $SOURCE_PATH = public_path(Config::get("constants.temp_folder")) . $file;
        $DESTINATION_PATH = public_path($destination) . $NEW_FILE_NAME;
        rename($SOURCE_PATH, $DESTINATION_PATH);
        return $NEW_FILE_NAME;
    }

    public function editTeamMember()
    {
        $result = Team::where('id', request('id'))->where('status', '!=', Team::DELETED)->first();

        /* Send Image With Full Path */
        $result->image_disp = $result->image ? asset('/team-uploads/' . $result->image) : '';

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "data" => $result,
            "length" => 1
        ], 200);
    }

    public function saveTeamMemeber(Request $request)
    {
        /* Validate Text Data */
        $this->validate($request, [
            "name" => ['required', 'max:255'],
            "designation" => ['required', 'max:255'],
            "bio" => ['nullable']
        ]);

        /* Validate Image */
        // if (empty($request->image_disp) && empty($request->image)) {
        //     return response()->json([
        //         'code'    => 422,
        //         'message' => "The given data was invalid.",
        //         'errors'  => ['image' => "The image field is required."]

        //     ], 422);
        // }

        /* Update Or Create Text Data */
        $team = Team::updateOrCreate(['id' => request('id')], [
            "name" => $request->name,
            "designation" => $request->designation,
            "bio" => $request->bio,
            "status" => $request->status
        ]);

        /* Upload Image */
        if (!empty($request->image)) {
            $file_name = $this->processImage(request('image'), 'TEAM', Config::get("constants.team_folder"));
            $team->update([
                "image" => $file_name
            ]);
        }

        /* Update Position */
        if (empty($request->id)) {
            $last = Team::max('position');
            $team = $team->update(['position' => $last + 1]);
        }

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "DATA" => $team
        ], 200);
    }

    public function listTeam($page)
    {
        $skip = ($page - 1) * request('perpage');
        $list = Team::where('status', '!=', Team::DELETED)->orderBy('position', 'ASC');

        $total_records = $list->count();
        $list = $list->skip($skip)->take(request('perpage'))->get()->toArray();

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "data" => $list,
            "searchedFields" => "",
            "total_records" => $total_records
        ], 200);
    }

    public function removeTeamImage()
    {
        if (!empty(request('field'))) {
            if (File::exists(public_path(Config::get("constants.temp_folder")) . request('field'))) {
                File::delete(public_path(Config::get("constants.temp_folder")) . request('field'));
            }

            /* Return JSON Response */
            return response()->json([
                "SUCCESS" => 1
            ], 200);
        } elseif (!empty(request('id'))) {
            $res = 0;
            $member = Team::where('id', request('id'))->first();
            if (!empty($member) && !empty($member->image)) {
                if (File::exists(public_path(Config::get("constants.team_folder")) . $member->image)) {
                    File::delete(public_path(Config::get("constants.team_folder")) . $member->image);
                }
                $res = $member->update(['image' => null]);
            }

            /* Return JSON Response */
            return response()->json([
                "SUCCESS" => $res
            ], 200);
        }
    }

    public function removeMember()
    {
        $member = Team::where('id', request('team_id'))->first();
        if (!empty($member)) {
            $member->update(['status' => Team::DELETED]);
        }

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1
        ], 200);
    }

    public function updateTeamStatus()
    {
        $member = Team::where('id', request('team_id'))->first();
        if (!empty($member)) {
            $member = $member->update(['status' => request('team_status')]);
        }

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1
        ], 200);
    }

    public function deleteAllMembers()
    {
        $ids_to_delete = array_map(function ($item) {
            return $item['id'];
        }, request('DIDS'));
        Team::whereIn('id', $ids_to_delete)->update(['status' => Team::DELETED]);

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1
        ], 200);
    }

    public function updateTeamPosition()
    {
        foreach (request('data') as $k => $val) {
            $res = Team::where('id', $val['team_id'])->first();
            if (!empty($res))
                $res->update(['position' => $val['team_position']]);
        }

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1
        ], 200);
    }
}
