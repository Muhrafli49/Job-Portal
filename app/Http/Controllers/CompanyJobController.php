<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyJobRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanyJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $user = Auth::user();

        $my_company = Company::where('employer_id', $user->id)->first();

        if($my_company){
            $company_jobs = CompanyJob::with(['category'])->where('company_id', $my_company->id)->paginate(10);
        }
        else {
            $company_jobs = collect();
        }

        return view('admin.company_jobs.index', compact('company_jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = Auth::user();

        $my_company = Company::where('employer_id', $user->id)->first();
        if(!$my_company){
            return redirect()->route('admin.company.index');
        }

        $categories = Category::all();

        return view ('admin.company_jobs.create', compact('categories', 'my_company'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyJobRequest $request)
    {
        //
        DB::transaction(function () use ($request) {
            $validated = $request->validated();

            if($request->hasFile('thumbnail')){
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails/' . date('Y/m/d'), 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            $validated['slug'] = Str::slug($validated['name']);
            $validated['is_open'] = true;

            $newJob = CompanyJob::create($validated);

            if(!empty($validated['responsibilities'])) {
                foreach ($validated['responsibilities'] as $responsibility) {
                    $newJob->responsibilities()->create([
                        'name' => $responsibility
                    ]);
                }
            }

            if(!empty($validated['qualifications'])){
                foreach ($validated['qualifications'] as $qualification) {
                    $newJob->qualifications()->create([
                        'name' => $qualification
                    ]);
                }
            }
        });

        return redirect()->route('admin.company_jobs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyJob  $companyJob
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyJob $companyJob)
    {
        //
        return view('admin.company_jobs.show', compact('companyJob'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyJob  $companyJob
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyJob $companyJob)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyJob  $companyJob
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyJob $companyJob)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyJob  $companyJob
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyJob $companyJob)
    {
        //
    }
}
