<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Kategori BLOG
    public function categories()
    {
        return view('pages.admin.blog_category.index');
    }

    public function store_category(Request $request) {}
    public function update_category(Request $request) {}
    public function delete_category(Request $request) {}

    // Tag BLOG
    public function tags()
    {
        return view('pages.admin.blog_tag.index');
    }

    public function store_tag(Request $request) {}
    public function update_tag(Request $request, $id) {}
    public function delete_tag(Request $request, $id) {}

    // BLOG
    public function index()
    {
        return view('blog.index');
    }

    public function show($slug)
    {
        return view('blog.show', compact('slug'));
    }

    public function create()
    {
        return view('pages.admin.blog.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'title' => 'required'
    }

    public function stores()
    {
        return view('pages.admin.blog.stores');
    }

    public function edit($id)
    {
        return view('pages.admin.blog.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
    }

    public function destroy($id)
    {
        // dd($id);
    }
}
