<?php

namespace App\Http\Controllers\API\ValetProfile;

use App\Helpers\Helper;
use App\Models\ValetProfile;
use App\Models\ValetProfileImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ValetProfileController
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'images.*' => 'required|image|mimes:jpeg,png,jpg,svg|max:5120',
        ]);

        if ($validator->fails()) {
            return Helper::jsonErrorResponse($validator->errors()->first(), 422);
        }

        $user = Auth::user();
        $paths = [];

        // Find the user's ValetProfile, or create a new one if it doesn't exist
        $valetProfile = $user->valetProfile ?? $user->valetProfile()->create();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();
                $path = Helper::fileUpload($image, 'ValetProfile', time() . '_' . $originalName);
                $paths[] = $path;

                // Save the images to the ValetProfileImage table
                $valetProfile->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return Helper::jsonResponse(true, 'Images uploaded successfully', 200);
    }


    public function getProfileImages(Request $request)
    {
        $user = Auth::user();
        $data = $user->valetProfile->with('images')->get();
        if (count($data) == 0) {
            return Helper::jsonResponse(false, 'No Valet Profile found!', 202);
        }
        return Helper::jsonResponse(true, 'Valet Profile Retrieved Successfully', 200, $data);
    }


    public function uploadPaperWork(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paper_work' => 'required|image|mimes:jpeg,png,jpg,svg|max:5120',
        ]);

        if ($validator->fails()) {
            return Helper::jsonErrorResponse($validator->errors()->first(), 422);
        }

        $user = Auth::user();
        $paths = [];

        // Find the user's ValetProfile, or create a new one if it doesn't exist
        $valetProfile = $user->valetProfile ?? $user->valetProfile()->create();

        if ($request->hasFile('paper_work')) {
            $originalName = $request->file('paper_work')->getClientOriginalName();
            $path = Helper::fileUpload($request->file('paper_work'), 'ValetProfile/PaperWork', time() . '_' . $originalName);
            $paths[] = $path;

            // Save the images to the ValetProfileImage table
            $valetProfile->paper_work = $path;
            $valetProfile->save();
        }

        return Helper::jsonResponse(true, 'Images uploaded successfully', 200);
    }

    public function meetRequirement(Request $request)
    {
        $user = Auth::id();
        $profile = ValetProfile::where('user_id', $user)->first();
        if (!$profile){
            ValetProfile::create([
                'user_id' => $user,
            ]);
            $profile = ValetProfile::where('user_id', $user)->first();
        }

        if ($request->meet_requirement == 0){
            $profile->meet_requirement = 0;
            $profile->update();
            return Helper::jsonResponse(true, 'You not meet our requirements, please try again', 200);
        } elseif ($request->meet_requirement == 1){
            $profile->meet_requirement = 1;
            $profile->update();
            return Helper::jsonResponse(true, 'Data saved successfully', 200);
        }

        return Helper::jsonResponse(false, 'Data not saved', 500);
    }

    public function requirement()
    {
        $user = Auth::id();
        $profile = ValetProfile::where('user_id', $user)->first();

        if (!$profile) {
            return Helper::jsonResponse(false, 'No Valet Profile found!', 404);
        }

        if ($profile->meet_requirement == 0){
            return Helper::jsonResponse(true, 'You not meet our requirements, please try again', 200);
        } elseif ($profile->meet_requirement == 1){
            return Helper::jsonResponse(true, 'Thank you', 200);
        }

        return Helper::jsonResponse(false, 'Data not saved', 500);
    }
}
