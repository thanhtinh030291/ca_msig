<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;
use Auth;
use App\User;
use Illuminate\Support\Arr;

class MailBoxController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Request $request)
    {
        $search_params = [
        'imap_page' => $request->get('imap_page'),
        'search' => $request->get('search'),
        //     'important' => $request->get('important')
        ];
        $client = \Webklex\IMAP\Facades\Client::account('default');

        //Connect to the IMAP Server
        $client->connect();

        //Get all Mailboxes
        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
        $folders = $client->getFolders($hierarchical = true);
        
        $folder =  $folders->where('name','INBOX')->first()->children->where('name', strtoupper(config('constants.company')))->first();
        $mess = $folder->query();
        if($request->get('search')){
            $mess = $mess->text($request->get('search'));
        }else{
            $mess = $mess->all();
        }
        $mess = $mess->get();
        $mess = $mess->paginate($per_page = 20, $page = $search_params['imap_page'], $page_name = 'imap_page');
        
        return view('mailBoxManagement/index',compact('folder','mess','search_params'));
    }

    public function error_messages(Request $request)
    {
        $search_params = [
        'imap_page' => $request->get('imap_page'),
        'search' => $request->get('search'),
        //     'important' => $request->get('important')
        ];
        $client = \Webklex\IMAP\Facades\Client::account('default');

        //Connect to the IMAP Server
        $client->connect();
        
        //Get all Mailboxes
        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
        $folders = $client->getFolders($hierarchical = true);
        
        $folder =  $folders->where('name','INBOX')->first()->children->where('name', 'ERROR MESSAGE')->first();
        
        $mess = $folder->messages()->setFetchOrder("desc")->all()->get();
        $mess = $mess->paginate($per_page = 20, $page = $search_params['imap_page'], $page_name = 'imap_page');
        
        return view('mailBoxManagement/index',compact('folder','mess','search_params'));
    }

    public function show($id)
    {
        $client = \Webklex\IMAP\Facades\Client::account('default');
        $id_mess = $id;
        //Connect to the IMAP Server
        $client->connect();

        //Get all Mailboxes
        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
        $folders = $client->getFolders($hierarchical = true);
        $folder =  $folders->where('name','INBOX')->first()->children->where('name', strtoupper(config('constants.company')))->first();
        $data = $folder->messages()->getMessageByUid($id);
        //dd($data->attachments);
        return view('mailBoxManagement.show', compact('data','folder','id_mess'));
    }

    public function download($id_mess , $id)
    {
        $client = \Webklex\IMAP\Facades\Client::account('default');
        
        //Connect to the IMAP Server
        $client->connect();

        //Get all Mailboxes
        /** @var \Webklex\PHPIMAP\Support\FolderCollection $folders */
        $folders = $client->getFolders($hierarchical = true);
        $folder =  $folders->where('name','INBOX')->first()->children->where('name', strtoupper(config('constants.company')))->first();
        $data = $folder->messages()->getMessageByUid($id_mess);
        $attachments = $data->attachments[$id];
        
        header("Content-Type: $attachments->content_type");
        header("Expires: 0");//no-cache
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//no-cache
        header("content-disposition: attachment;filename=$attachments->name");
        echo $attachments->content;
    }
   
}