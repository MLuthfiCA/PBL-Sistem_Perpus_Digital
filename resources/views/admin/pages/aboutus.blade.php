@extends('admin.layouts.app')

@section('title', 'About Us')

@section('content')
    <div class="py-10">
        <h2 class="text-3xl font-extrabold mb-10 text-center text-[#632024]">About Us</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 text-center hover:shadow-2xl hover:-translate-y-2 transition duration-500 group">
                <div class="bg-[#632024] h-40 mb-6 rounded-2xl shadow-inner group-hover:bg-red-900 transition-colors"></div>
                <p class="font-bold text-xl text-[#632024] mb-2">M. Luthfi Causart Azavi</p>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-stone-500">NIM : 3312501052</p>
                    <p class="text-sm font-medium text-stone-500">Prodi : Teknik Informatika</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 text-center hover:shadow-2xl hover:-translate-y-2 transition duration-500 group">
                <div class="bg-[#632024] h-40 mb-6 rounded-2xl shadow-inner group-hover:bg-red-900 transition-colors"></div>
                <p class="font-bold text-xl text-[#632024] mb-2">Muhammad Risky Kurnia</p>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-stone-500">NIM : 3312501056</p>
                    <p class="text-sm font-medium text-stone-500">Prodi : Teknik Informatika</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 text-center hover:shadow-2xl hover:-translate-y-2 transition duration-500 group">
                <div class="bg-[#632024] h-40 mb-6 rounded-2xl shadow-inner group-hover:bg-red-900 transition-colors"></div>
                <p class="font-bold text-xl text-[#632024] mb-2">Siti Halimah Chania</p>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-stone-500">NIM : 3312501057</p>
                    <p class="text-sm font-medium text-stone-500">Prodi : Teknik Informatika</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-stone-100 p-8 text-center hover:shadow-2xl hover:-translate-y-2 transition duration-500 group">
                <div class="bg-[#632024] h-40 mb-6 rounded-2xl shadow-inner group-hover:bg-red-900 transition-colors"></div>
                <p class="font-bold text-xl text-[#632024] mb-2">Zahrah Athirah Badiah</p>
                <div class="space-y-1">
                    <p class="text-sm font-medium text-stone-500">NIM : 3312501060</p>
                    <p class="text-sm font-medium text-stone-500">Prodi : Teknik Informatika</p>
                </div>
            </div>
        </div>
    </div>
@endsection
