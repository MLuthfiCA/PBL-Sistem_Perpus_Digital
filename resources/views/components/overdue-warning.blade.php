@if(session()->has('user') && isset(session('user')['overdue_books']) && count(session('user')['overdue_books']) > 0)
<div class="mb-8 p-6 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-md">
    <div class="flex items-start gap-4">
        <!-- Icon -->
        <div class="flex-shrink-0 pt-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>

        <!-- Content -->
        <div class="flex-1">
            <h3 class="text-lg font-bold text-red-800 mb-2">⚠️ Attention: Overdue Books!</h3>
            <p class="text-sm text-red-700 mb-4">
                You have {{ count(session('user')['overdue_books']) }} book(s) that have not been returned on time.
            </p>

            <!-- Books List -->
            <div class="space-y-3 mb-4">
                @php
                    $totalCalculatedDenda = 0;
                @endphp
                @foreach(session('user')['overdue_books'] as $overdue)
                @php
                    $overdueDays = $overdue->calculateOverdueDays();
                    $denda = $overdue->calculateDenda();
                    $totalCalculatedDenda += $denda;
                @endphp
                <div class="bg-white rounded p-3 border border-red-200">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $overdue->buku?->judul ?? 'Book' }}
                            </p>
                            @if($overdue->buku)
                            <p class="text-sm text-gray-600">
                                {{ $overdue->buku->penulis ?? 'Unknown Author' }}
                            </p>
                            @endif
                        </div>
                        <span class="text-xs font-bold px-3 py-1 bg-red-600 text-white rounded">
                            {{ $overdueDays }} {{ $overdueDays > 1 ? 'DAYS' : 'DAY' }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-700">
                        <p>Due Date: <strong>{{ $overdue->batas_kembali instanceof \Carbon\Carbon ? $overdue->batas_kembali->format('d/m/Y') : $overdue->batas_kembali }}</strong></p>
                        <p class="text-red-700 font-semibold mt-1">Fine: Rp. {{ number_format($denda, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total Fine & CTA -->
            <div class="bg-white rounded p-4 border border-red-300 flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600">Total Fine</p>
                    <p class="text-2xl font-bold text-red-600">
                        Rp. {{ number_format($totalCalculatedDenda, 0, ',', '.') }}
                    </p>
                </div>
                <a href="{{ route('profile') }}" class="px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-all">
                    View Details
                </a>
            </div>

            <p class="text-xs text-red-600 mt-3 font-semibold">
                📌 Please return the book(s) immediately and pay your fine(s) to avoid account suspension.
            </p>
        </div>

        <!-- Close Button -->
        <button onclick="this.parentElement.parentElement.style.display='none'" class="flex-shrink-0 text-red-600 hover:text-red-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
@endif
