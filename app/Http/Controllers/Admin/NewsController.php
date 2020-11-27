<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\News;
use App\NewsDisclosure;
use App\Http\Controllers\ImageResizer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    private $disclosure_id;

    public function __construct()
    {
        $this->disclosure_id = 1001;
    }

    private function processImage($file, $type, $destination, $resize = false, $width = 220, $height = 110)
    {
        $NEW_FILE_NAME = "";
        $NEW_FILE_NAME = $type . "-" . time() . "-" . $file;
        $SOURCE_PATH = public_path(Config::get("constants.temp_folder")) . $file;
        $DESTINATION_PATH = public_path($destination) . $NEW_FILE_NAME;
        rename($SOURCE_PATH, $DESTINATION_PATH);
        if ($resize == true) {
            $file_type = getimagesize($DESTINATION_PATH);
            $work = new ImageResizer($DESTINATION_PATH, $file_type['mime']);
            $work->resize($width, $height, public_path(Config::get("constants.news_folder")) . "R-" . $NEW_FILE_NAME, "");
        }
        return $NEW_FILE_NAME;
    }


    public function listNews($page)
    {
        $skip = ($page - 1) * request('perpage');
        $list = News::where('status', '!=', News::DELETED)->orderBy('is_disclosure', 'DESC')->orderBy('set_at_homepage', 'DESC')->orderBy('date', 'DESC');

        $searchedFields = [
            'search_news_title' => request('search_news_title') ? request('search_news_title') : '',
            'search_news_publication' => request('search_news_publication') ? request('search_news_publication') : '',
            'search_news_status' => request('search_news_status') != '' ? request('search_news_status') : ''

        ];
        if (!empty(request('search_news_title'))) {
            $list = $list->where('title', 'like', '%' . request('search_news_title') . '%');
        }
        if (request('search_news_status') != '') {
            $list = $list->where('status', request('search_news_status'));
        }
        if (!empty(request('search_news_publication'))) {
            $list = $list->where('publication', 'like', '%' . request('search_news_publication') . '%');
        }

        $total_records = $list->count();
        $list = $list->skip($skip)->take(request('perpage'))->get()->toArray();

        $returnArr = array("SUCCESS" => 1, "data" => $list, 'searchedFields' => $searchedFields, 'total_records' => $total_records);
        echo json_encode($returnArr);
    }

    /*************************************************************/
    public function saveNews()
    {

        if (!empty(request('news_date'))) {
            $a_exc = explode('-', request('news_date'));
            $asOfDate = $a_exc[2] . '-' . $a_exc[0] . '-' . $a_exc[1];
        } else {
            $asOfDate = null;
        }

        $upd = News::updateOrCreate(['id' => request('id')], [
            "title" => request('news_title'),
            "publication" => request('news_publication'),
            "date" => $asOfDate,
            "news_type" => request('news_type'),
            "status" => request('status'),
            'set_at_homepage' => !empty(request('set_at_homepage')) ? true : false,
            'link_title' => request('link_title'),
            'is_disclosure' => !empty(request('is_disclosure')) ? true : false,

        ]);

        if (request('is_disclosure') == true && !empty(request('news_disclosure'))) {
            $upd->update(['news_disclosure' => request('news_disclosure')]);
        }

        if ((request('news_type') == 'FILE') && !empty(request("news_file"))) {
            if (!empty($upd->news_file) && File::exists(public_path(Config::get("constants.news_folder")) . $upd->news_file)) {
                File::delete(public_path(Config::get("constants.news_folder")) . $upd->news_file);
            }

            $NEW_FILE_NAME = $this->processImage(request('news_file'), 'NEWS_FILE', Config::get("constants.news_folder"));

            $upd->update([
                "news_file" => $NEW_FILE_NAME
            ]);
        }


        if ((request('news_type') == 'URL') && !empty(request("news_url"))) {
            $upd->update([
                "news_url" => request('news_url')
            ]);
        }
        if ((request('news_type') == 'VIDEO')) {
            if (!empty(request('video_file'))) {
                if (!empty($upd->video_file) && File::exists(public_path(Config::get("constants.news_folder")) . $upd->video_file)) {
                    File::delete(public_path(Config::get("constants.news_folder")) . $upd->video_file);
                }

                $NEW_FILE_NAME = $this->processImage(request('video_file'), 'NEWS_VIDEO', Config::get("constants.news_folder"));

                $upd->update([
                    "video_file" => $NEW_FILE_NAME
                ]);
            }

            if (!empty(request('video_image'))) {
                if (!empty($upd->video_image) && File::exists(public_path(Config::get("constants.news_folder")) . $upd->video_image)) {
                    File::delete(public_path(Config::get("constants.news_folder")) . $upd->video_image);
                }

                $NEW_FILE_NAME = $this->processImage(request('video_image'), 'NEWS_POSTER', Config::get("constants.news_folder"), true);

                $upd->update([
                    "video_image" => $NEW_FILE_NAME
                ]);
            }
        }

        if (!empty(request('news_image'))) {
            if (!empty($upd->news_image) && File::exists(public_path(Config::get("constants.news_folder")) . $upd->news_image)) {
                File::delete(public_path(Config::get("constants.news_folder")) . $upd->news_image);
            }

            $NEW_FILE_NAME = $this->processImage(request('news_image'), 'NBG_', Config::get("constants.news_folder"), true);

            $upd->update([
                "news_image" => $NEW_FILE_NAME
            ]);
        }

        if (empty(request('id'))) {
            $last = News::max('position');
            $upd = $upd->update(['position' => $last + 1]);
        }


        $returnArr = array("SUCCESS" => 1, "DATA" => $upd);
        echo json_encode($returnArr);
    }

    /******************************************************************/
    public function editNews()
    {
        $result = News::where('id', request('id'))->where('status', '!=', News::DELETED)->first()->toArray();
        $returnArr = array("SUCCESS" => 1, "data" => $result, "length" => 1);
        echo json_encode($returnArr);
    }

    /*****************************************************************/
    public function removeImage()
    {
        if (!empty(request('field'))) {
            if (File::exists(public_path(Config::get("constants.temp_folder")) . request('field'))) {
                File::delete(public_path(Config::get("constants.temp_folder")) . request('field'));
            }
            $returnArr = array("SUCCESS" => 1);
        } elseif (!empty(request('id'))) {
            $res = 0;
            $news = News::where('id', request('id'))->first();

            switch (request('type')) {
                case 'FILE':
                    if (!empty($news) && !empty($news->news_file)) {
                        if (File::exists(public_path(Config::get("constants.news_folder")) . $news->news_file)) {
                            File::delete(public_path(Config::get("constants.news_folder")) . $news->news_file);
                        }
                        $res = $news->update(['news_file' => null]);
                    }
                    break;
                case 'VIDEO':
                    if (!empty($news) && !empty($news->video_file)) {
                        if (File::exists(public_path(Config::get("constants.news_folder")) . $news->video_file)) {
                            File::delete(public_path(Config::get("constants.news_folder")) . $news->video_file);
                        }
                        $res = $news->update(['video_file' => null]);
                    }
                    break;

                case 'VIDEO_POSTER':
                    if (!empty($news) && !empty($news->video_image)) {
                        if (File::exists(public_path(Config::get("constants.news_folder")) . $news->video_image)) {
                            File::delete(public_path(Config::get("constants.news_folder")) . $news->video_image);
                        }
                        $res = $news->update(['video_image' => null]);
                    }
                    break;
                case 'NEWS_IMAGE':
                    if (!empty($news) && !empty($news->news_image)) {
                        if (File::exists(public_path(Config::get("constants.news_folder")) . $news->news_image)) {
                            File::delete(public_path(Config::get("constants.news_folder")) . $news->news_image);
                        }

                        if (File::exists(public_path(Config::get("constants.news_folder")) . 'R-' . $news->news_image)) {
                            File::delete(public_path(Config::get("constants.news_folder")) . 'R-' . $news->news_image);
                        }
                        $res = $news->update(['news_image' => null]);
                    }
                    break;
                default:
                    # code...
                    break;
            }

            $returnArr = array("SUCCESS" => 1);
        }
        echo json_encode($returnArr);
    }

    /*****************************************************/

    public function deleteNews()
    {
        $news = News::where('id', request('news_id'))->first();
        if (!empty($news)) {
            $news->update(['status' => News::DELETED]);
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }

    /*****************************************************/
    public function updateStatus()
    {
        $news = News::where('id', request('news_id'))->first();
        if (!empty($news)) {
            $news->update(['status' => request('news_status')]);
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }
    /*******************************************************/
    public function deleteAll()
    {
        $ids_to_delete = array_map(function ($item) {
            return $item['id'];
        }, request('DIDS'));
        News::whereIn('id', $ids_to_delete)->update(['status' => News::DELETED]);
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }
    /*******************************************************/

    public function setForHome()
    {
        $news = News::where('id', request('news_id'))->first();
        if (!empty($news)) {
            $news->update(['set_at_homepage' => request('news_home')]);
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }

    /*******************************************************/
    public function newsPositionList()
    {
        $list = News::where('status', '!=', News::DELETED)->where('set_at_homepage', News::ACTIVE)->orderBy('position', 'ASC');
        $total_records = $list->count();
        $list = $list->get()->toArray();

        $returnArr = array("SUCCESS" => 1, "data" => $list, 'total_records' => $total_records);
        echo json_encode($returnArr);
    }

    public function savePosition()
    {
        foreach (request('data') as $k => $val) {
            $res = News::where('id', $val['id'])->first();
            if (!empty($res))
                $res->update(['position' => $val['position']]);
        }
        $returnArr = array("SUCCESS" => 1);
        echo json_encode($returnArr);
    }
    /****************************************************/

    public function newsDisclosureEdit()
    {
        $row = NewsDisclosure::first();

        /* Send Banner Image With Full Path */
        $row->banner_img_disp = $row && $row->banner_img ? asset(Config::get("constants.home_content_folder") . $row->banner_img) : '';

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "DATA" => $row
        ], 200);
    }

    public function saveNewsDisclosure(Request $request)
    {
        /* Validate Text Data */
        $this->validate($request, [
            "title" => ['required', 'max:255'],
            "disclosure" => ['nullable'],
            "meta_title" => ['nullable', 'max:100'],
            "meta_keyword" => ['nullable', 'max:200'],
            "meta_description" => ['nullable', 'max:200'],
        ]);

        /* Validate Banner Image */
        if (empty($request->banner_img_disp) && empty($request->banner_img)) {
            return response()->json([
                'code'    => 422,
                'message' => "The given data was invalid.",
                'errors'  => ['banner_img' => "The banner image field is required."]

            ], 422);
        }

        /* Update Or Create Text Data */
        $news = NewsDisclosure::updateOrCreate(['id' => $this->disclosure_id], [
            "title" => $request->title,
            'disclosure' => $request->disclosure,
            "meta_title" => $request->meta_title,
            "meta_keyword" => $request->meta_keyword,
            "meta_description" => $request->meta_description
        ]);

        /* Upload Banner Image */
        if (!empty($request->banner_img)) {
            $file_name = $this->processImage($request->banner_img, 'BANNER', Config::get("constants.news_folder"));

            $news->update([
                "banner_img" => $file_name
            ]);
        }

        /* Return JSON Response */
        return response()->json([
            "SUCCESS" => 1,
            "DATA" => $news
        ], 200);
    }

    public function removeNewsDisclosure()
    {
        if (!empty(request('field'))) {
            if (File::exists(public_path(Config::get("constants.temp_folder")) . request('field'))) {
                File::delete(public_path(Config::get("constants.temp_folder")) . request('field'));
            }

            /* Return JSON Response */
            return response()->json([
                "SUCCESS" => 1
            ], 200);
        } else {
            $res = 0;
            $content = NewsDisclosure::first();
            if (!empty($content)) {
                $file = $content->banner_img ? $content->banner_img : '';
                if (!empty($file)) {
                    if (File::exists(public_path(Config::get("constants.news_folder")) . $file)) {
                        File::delete(public_path(Config::get("constants.news_folder")) . $file);
                    }
                }
                $res = $content->update(['banner_img' => '']);
            }

            /* Return JSON Response */
            return response()->json([
                "SUCCESS" => ($res == true ? 1 : 0)
            ], 200);
        }
    }
}
