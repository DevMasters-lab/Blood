@extends('layouts.user') {{-- Use your main frontend layout --}}

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4">
    <div class="mb-6">
        <a href="javascript:history.back()" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back
        </a>
    </div>
    <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 md:p-12">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-red-100">
                <i class="fa-solid fa-file-invoice text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Submit Donation</h2>
            <p class="text-gray-500 mt-2 text-sm font-medium">Upload proof of your hospital or blood bank donation to add it to your wallet.</p>
        </div>

        <form action="{{ route('user.donate.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Blood Bank / Hospital Name</label>
                <input type="text" name="blood_bank_name" required class="w-full bg-gray-50 border border-gray-100 p-4 rounded-xl focus:ring-4 focus:ring-red-500/10 focus:border-red-500 outline-none transition-all font-bold text-gray-800 placeholder:font-medium" placeholder="Ex: Calmette Hospital">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Donation Date</label>
                    <input type="date" name="donation_date" required class="w-full bg-gray-50 border border-gray-100 p-4 rounded-xl focus:ring-4 focus:ring-red-500/10 focus:border-red-500 outline-none transition-all font-bold text-gray-800">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Manual Expiry Date</label>
                    <input type="date" name="expiry_date" required class="w-full bg-gray-50 border border-gray-100 p-4 rounded-xl focus:ring-4 focus:ring-red-500/10 focus:border-red-500 outline-none transition-all font-bold text-gray-800">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Blood Type (Optional)</label>
                <select name="blood_type" class="w-full bg-gray-50 border border-gray-100 p-4 rounded-xl focus:ring-4 focus:ring-red-500/10 focus:border-red-500 outline-none transition-all font-bold text-gray-800 appearance-none cursor-pointer">
                    <option value="" selected>I'm not sure / Leave blank</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="p-5 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[1.5rem] hover:border-red-200 transition-colors cursor-pointer group">
                <label class="block text-center cursor-pointer w-full">
                    <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-300 group-hover:text-red-400 transition-colors mb-2"></i>
                    <span class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-1">Upload Donation Proof</span>
                    <span class="text-[10px] text-slate-400 font-bold block mb-3">Photo or PDF of your certificate/card</span>
                    <input type="file" name="proof_file" accept="image/*,.pdf" class="hidden" id="proofFileInput" required>
                    <span class="inline-block bg-white border border-slate-200 px-4 py-2 rounded-lg text-[10px] font-black text-slate-600 shadow-sm hover:shadow-md transition-all">Select File</span>
                </label>
            </div>

            <!-- Image Preview Section -->
            <div id="previewContainer" class="hidden">
                <div class="relative">
                    <img id="previewImage" src="" alt="Preview" class="w-full h-64 object-contain rounded-xl border border-gray-200">
                    <button type="button" id="clearPreviewBtn" class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all">
                        <i class="fa-solid fa-times text-sm"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-500 font-medium mt-2"><span id="fileName">No file selected</span></p>
            </div>

            <button type="submit" class="w-full bg-red-600 text-white p-4 rounded-2xl font-black text-sm uppercase tracking-[0.15em] hover:bg-red-700 transition-all shadow-xl shadow-red-200 hover:-translate-y-0.5 active:scale-95">
                Submit Invoice for Review
            </button>
        </form>
    </div>
</div>

<script>
    const proofFileInput = document.getElementById('proofFileInput');
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');
    const clearPreviewBtn = document.getElementById('clearPreviewBtn');

    proofFileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        
        if (file) {
            // Check if it's an image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    fileName.textContent = file.name;
                    previewContainer.classList.remove('hidden');
                };
                
                reader.readAsDataURL(file);
            } else {
                // If PDF or other file, just show the filename
                fileName.textContent = file.name;
                previewImage.src = '';
                previewContainer.classList.remove('hidden');
            }
        } else {
            previewContainer.classList.add('hidden');
        }
    });

    clearPreviewBtn.addEventListener('click', function(event) {
        event.preventDefault();
        proofFileInput.value = '';
        previewContainer.classList.add('hidden');
        previewImage.src = '';
    });
</script>
@endsection