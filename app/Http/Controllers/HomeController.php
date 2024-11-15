<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CompanyJob;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){
        $categories = Category::all();
        $jobs = CompanyJob::with(['category', 'company'])->InRandomOrder()->take(6)->get();
        return view('home.index', compact('jobs', 'categories'));
    }
    

    public function details(CompanyJob $companyJob){
        $jobs = CompanyJob::with(['category', 'company'])
        ->where('id', '!=', $companyJob->id)
        ->InRandomOrder()
        ->take(4)
        ->get();
        
        return view('home.details', compact('companyJob', 'jobs'));
    }
}
