<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutorials;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class TutorialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result['datas'] = Tutorials::all();
        return view('home', $result);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $insert = new Tutorials();
        $insert->tutorial_title = $request->tutorial_title;
        $insert->tutorial_author = $request->tutorial_author;
        $insert->submission_date = $request->submission_date;
        $insert->save();
        $msg = json_encode(['status' => 200, 'message' => "Record saved successfully."]);
        $errormsg = json_encode(['status' => 202, 'message' => 'Something went wrong']);
        return $insert ? $msg : $errormsg;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $file = $request->file('file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize(); //Get size of uploaded file in bytes

            //Check for file extension and size
            $this->checkUploadedFileProperties($extension, $fileSize);
            //Where uploaded file will be stored
            $location = 'uploads';
            // Upload file
            $file->move($location, $filename);
            // In case the uploaded file path is to be stored in the database 
            $filepath = public_path($location . "/" . $filename);
            $file = fopen($filepath, "r");

            $importData_arr = array(); // Read through the file and store the contents as an array
            $i = 0;
            //Read the contents of the uploaded file 
            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                $num = count($filedata);
                // Skip first row 
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file); //Close after reading
            $j = 0;

            foreach ($importData_arr as $importData) {
                // dd($importData);
                $j++;
                try {
                    $submission_date = date("Y-m-d", strtotime($importData[2]));
                    $Tutorials = new Tutorials();
                    $Tutorials->tutorial_title = $importData[0];
                    $Tutorials->tutorial_author = $importData[1];
                    $Tutorials->submission_date = $submission_date;
                    $Tutorials->save();
                } catch (\Exception $e) {
                    throw $e;
                }
            }
            if ($Tutorials) {
                return  json_encode(['status' => 200, 'message' => "$j records successfully uploaded"]);
            }
        } else {
            //no file was uploaded
            return  json_encode(['status' => 202, 'message' => "Please choose csv file."]);
            // throw new \Exception('No file was uploaded', Response::HTTP_BAD_REQUEST);
        }
    }
    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv"); //Only want csv and excel files
        $maxFileSize = 2097152; // Uploaded file size limit is 2mb
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
            } else {
                throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
            }
        } else {
            throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $req)
    {
        if ($req->_token) {
            $result['datas'] = Tutorials::orderBy('tutorial_id', 'DESC')->paginate(10);
            return view('pagination', $result);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->tutorial_id) {
            $Tutorials =  Tutorials::where('tutorial_id', $request->tutorial_id)->update(["tutorial_title" => $request->tutorial_title, "tutorial_author" => $request->tutorial_author, "submission_date" => $request->submission_date]);
            echo json_encode(['status' => 200, 'message' => "Record updated successfully."]);
            return;
        }else{
           echo  json_encode(['status' => 202, 'message' => 'Something went wrong.']);
            return;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $delete = Tutorials::where('tutorial_id', $request->tutorial_id)->delete();
        $msg = json_encode(['status' => 200, 'message' => "Record deleted successfully."]);
        $errormsg = json_encode(['status' => 202, 'message' => 'Something went wrong']);
        return $delete ? $msg : $errormsg;
    }
}
