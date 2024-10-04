<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dashboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $dashboards = Dashboard::paginate(10);
        return view('admin.dashboards.index',compact('dashboards'));
    }
}
