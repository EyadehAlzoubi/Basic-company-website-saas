<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;
use App\Models\Clarifi;
use App\Models\Usability;
use App\Models\Connect;
use App\Models\Faq;
use App\Models\App;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class HomeController extends Controller
{
    public function AllFeature()
    {
        $feature = Feature::latest()->get();
        return view('admin.backend.feature.all_feature',compact('feature'));
    }


    public function AddFeature()
    {
        return view('admin.backend.feature.add_feature');
    }


    public function StoreFeature(Request $request)
    {

        Feature::create([
            'title' =>$request->title,
            'icon' =>$request->icon,      
            'description' =>$request->description,
        ]);

        $notification = array(
            'message'      => 'Feature Inserted Successfully :)',
            'alert-type'   => 'success'
        );

        return redirect()->route('all.feature')->with($notification);
    }



    public function EditFeature($id)
    {
        $feature = Feature::find($id);
        return view('admin.backend.feature.edit_feature',compact('feature'));
    }



    public function UpdateFeature(Request $request)
    {


        $fea_id = $request->id;

        Feature::find($fea_id)->update([
            'title' =>$request->title,
            'icon' =>$request->icon,      
            'description' =>$request->description,
        ]);

        $notification = array(
            'message'      => 'Feature Updated Successfully :)',
            'alert-type'   => 'success'
        );

        return redirect()->route('all.feature')->with($notification);
    }


    public function DeleteFeature($id){

        Feature::find($id)->delete();

        $notification = array(
            'message'      => 'Feature Deleted Successfully :)',
            'alert-type'   => 'success'
        );

        return redirect()->back()->with($notification);
    }



    public function GetClarigies()
    {

        $clarifi = Clarifi::find(1);

        return view('admin.backend.clarifi.get_clarifi' , compact('clarifi'));


    }


    public function UpdateClarigies(Request $request)
    {

        $clar_id = $request->id;

        $clarifi = Clarifi::find($clar_id);

        if($request->file('image'))
        {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(302,618)->save(public_path('upload/clarifi/'.$name_gen));
            $save_url = 'upload/clarifi/'.$name_gen;

            if(file_exists(public_path($clarifi->image)))
            {
                @unlink(public_path($clarifi->image));
            }


            Clarifi::find($clar_id)->update([
                'title' =>$request->title,
                'description' =>$request->description,      
                'image' =>$save_url,
            ]);
    
            $notification = array(
                'message'      => 'Clarifi Updated Successfully :)',
                'alert-type'   => 'success'
            );

        }else
        {

            Clarifi::find($clar_id)->update([
                'title' =>$request->title,
                'description' =>$request->description,      
            ]);
    
            $notification = array(
                'message'      => 'Clarifi Updated Successfully :)',
                'alert-type'   => 'success'
            );


        }



        return redirect()->back()->with($notification);
    }


    public function GetUsability(Request $request){
        
        $usability = Usability::find(1);

        return view('admin.backend.usability.get_usability' , compact('usability'));

    }


    public function UpdateUsability(Request $request)
    {

        $usability_id = $request->id;

        $usability = Usability::find($usability_id);

        if($request->file('image'))
        {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(560,400)->save(public_path('upload/usability/'.$name_gen));
            $save_url = 'upload/usability/'.$name_gen;

            if(file_exists(public_path($usability->image)))
            {
                @unlink(public_path($usability->image));
            }


            Usability::find($usability_id)->update([
                'title' =>$request->title,
                'description' =>$request->description, 
                'youtube' =>$request->youtube,      
                'link' =>$request->link,      
                'image' =>$save_url,
            ]);
    
            $notification = array(
                'message'      => 'Usability Updated Successfully :)',
                'alert-type'   => 'success'
            );

        }else
        {


            Usability::find($usability_id)->update([
                'title' =>$request->title,
                'description' =>$request->description, 
                'youtube' =>$request->youtube,      
                'link' =>$request->link,      
            ]);
    
            $notification = array(
                'message'      => 'Usability Updated Successfully :)',
                'alert-type'   => 'success'
            );


        }



        return redirect()->back()->with($notification);
    }


    public function AllConnect()
    {
        $connect = Connect::latest()->get();
        return view('admin.backend.connect.all_connect',compact('connect'));
    }


    public function AddConnect()
    {
        return view('admin.backend.connect.add_connect');


    }

    public function StoreConnect(Request $request){


        Connect::create([
            'title' =>$request->title,
            'description' =>$request->description,
        ]);

        $notification = array(
            'message'      => 'Connect Inserted Successfully :)',
            'alert-type'   => 'success'
        );

        return redirect()->route('get.connect')->with($notification);

    }

    public function UpdateConnect(Request $request,$id)
    {
        $connect = Connect::findOrFail($id);
        $connect->update($request->only(['title','description']));
        return response()->json(['success' => true , 'message' => 'Updated Successfully']);
    }


    public function AllFaqs()
    {
        $faqs = Faq::latest()->get();
        return view('admin.backend.faqs.all_faqs',compact('faqs'));
    }

    public function AddFaqs()
    {
        return view('admin.backend.faqs.add_faqs');
    }

    public function StoreFaqs(Request $request)
    {

        Faq::create([
            'title' =>$request->title,
            'description' =>$request->description,
        ]);

        $notification = array(
            'message'      => 'Faqs Inserted Successfully :)',
            'alert-type'   => 'success'
        );

        return redirect()->route('all.faqs')->with($notification);
    }



    public function EditFaqs($id)
    {
        $faqs = Faq::find($id);
        return view('admin.backend.faqs.edit_faqs',compact('faqs'));
    }


    public function UpdateFaqs(Request $request)
    {


        $faq_id = $request->id;

        Faq::find($faq_id)->update([
            'title' =>$request->title,
            'description' =>$request->description,
        ]);

        $notification = array(
            'message'      => 'Faqs Updated Successfully :)',
            'alert-type'   => 'success'
        );

        return redirect()->route('all.faqs')->with($notification);
    }


    public function DeleteFaqs($id){

        Faq::find($id)->delete();

        $notification = array(
            'message'      => 'Faq Deleted Successfully :)',
            'alert-type'   => 'success'
        );

        return redirect()->back()->with($notification);
    }


    public function UpdateApps(Request $request, $id)
    {
        $app = App::findOrFail($id);

        $app->update($request->only(['title', 'description']));

        return response()->json(['success' => true, 'message' => 'Updated successfully']);
    }

    public function UpdateAppsImage(Request $request, $id){
        $apps = App::findOrFail($id);
    
        if ($request->file('image')) {
            $image = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(306,481)->save(public_path('upload/apps/'.$name_gen));
            $save_url = 'upload/apps/'.$name_gen;
    
            if (file_exists(public_path($apps->image))) {
                @unlink(public_path($apps->image));
            }
    
            $apps->update([
                'image' => $save_url,
            ]);
    
            return response()->json([
                'success' =>  true,
                'image_url' => asset($save_url),
                'message' => 'Image Updated Successfully'
            ]);   
        }
    
        return response()->json(['success' => false, 'message' => 'Image Upload Failed'],400);
    
    }
   
}
