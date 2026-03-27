<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Posts;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $currentDomain = request()->getHost();  

        $totalUsers = User::count();
        $totalNews = Posts::where('domain', $currentDomain)->count();  
        $newsThisYear = Posts::where('domain', $currentDomain)->whereYear('created_at', date('Y'))->count();  // ← TAMBAHAN filter domain
        $newsToday = Posts::where('domain', $currentDomain)->whereDate('created_at', date('Y-m-d'))->count();  // ← TAMBAHAN filter domain
    
        return view('pages.admin.home.index', [
            'totalUsers' => $totalUsers,
            'totalNews' => $totalNews,
            'newsThisYear' => $newsThisYear,
            'newsToday' => $newsToday,
            'page' => 'Dashboard',
        ]);
    }
    
}