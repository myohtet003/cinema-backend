{{-- Include html2canvas for the "Save as Image" feature --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<x-app-layout>
    <div class="min-h-screen bg-neutral-900 py-12 px-4 flex items-center justify-center">
        <div class="w-2/4 max-w-sm mx-auto">

            {{-- THE SLIP --}}
            <div id="ticket-slip" class="bg-white text-black shadow-2xl relative overflow-hidden">

                {{-- Top Zig Zag / Header --}}
                <div class="pt-8 px-8 text-center border-b-2 border-dashed border-gray-200 pb-6">
                    <h1 class="text-2xl font-black uppercase tracking-tighter text-black">Cinema Ticket</h1>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">
                        Official Receipt
                    </p>
                </div>

                <div class="p-8 space-y-6">
                    {{-- MOVIE & THEATER INFO --}}
                    <div class="text-center">
                        <h2 class="text-xl font-extrabold uppercase leading-tight text-black">
                            {{ $booking->booking_type === 'private' ? 'Private Cinema Experience' : $booking->showtime->movie->title }}
                        </h2>
                        <div class="mt-2 text-gray-600">
                            <p class="text-xs font-bold">{{ $booking->showtime->screen->cinema->name }}</p>
                            <p class="text-[10px] uppercase leading-relaxed opacity-75">
                                123 Movie Lane, Junction Square,<br>
                                Yangon, Myanmar
                            </p>
                        </div>
                    </div>

                    {{-- MAIN DETAILS GRID --}}
                    <div class="grid grid-cols-2 gap-4 py-4 border-y border-gray-100">
                        <div>
                            <p class="text-[9px] text-gray-400 uppercase font-black">Date</p>
                            <p class="text-sm font-bold text-black">
                                {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d M Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] text-gray-400 uppercase font-black">Time</p>
                            <p class="text-sm font-bold text-black">
                                {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('h:i A') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[9px] text-gray-400 uppercase font-black">Screen</p>
                            <p class="text-sm font-bold text-black">{{ $booking->showtime->screen->name }}</p>
                        </div>
                        <div class="text-right">
                            @if ($booking->booking_type === 'private')
                                <p class="text-[9px] text-gray-400 uppercase font-black">Room Type</p>
                                <p class="text-sm font-bold text-black">
                                    {{ ['2p' => '2 Persons', '4p' => '4 Persons', '6p' => '6 Persons'][$booking->showtime->screen->room_type] ?? 'Private Room' }}
                                </p>
                            @else
                                <p class="text-[9px] text-gray-400 uppercase font-black">Seats</p>
                                <p class="text-sm font-bold text-black">
                                    @foreach ($booking->bookingSeats as $bs)
                                        {{ $bs->seat->seatRow->row_name }}{{ $bs->seat->seat_number }}{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- TRANSACTION INFO --}}
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px]">
                            <span class="text-gray-400 font-bold uppercase">Transaction ID</span>
                            <span
                                class="font-mono font-bold text-black">#{{ $booking->payment->transaction_id ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-[10px]">
                            <span class="text-gray-400 font-bold uppercase">Payment Method</span>
                            <span class="font-bold uppercase text-black">
                                {{ $booking->payment->paymentMethod->name ?? 'Cash' }}
                            </span>
                        </div>
                    </div>

                    {{-- PRICE SECTION --}}
                    <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center">
                        <span class="text-xs font-black uppercase text-gray-500">Total Paid</span>
                        <span class="text-xl font-black text-black">{{ number_format($booking->total_price) }}
                            MMK</span>
                    </div>

                    {{-- QR CODE & STATUS --}}
                    <div class="flex flex-col items-center pt-4">
                        <div class="p-2 border-2 border-gray-100 rounded-xl mb-4 bg-white">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $booking->id }}"
                                alt="QR Code" class="w-24 h-24 grayscale">
                        </div>
                        <p class="text-[9px] font-mono text-gray-400 tracking-widest uppercase">
                            Booking ID: {{ $booking->id }}
                        </p>
                        <div class="mt-4">
                            <span
                                class="px-6 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $booking->status === 'paid' ? 'border-green-500 text-green-500' : 'border-yellow-500 text-yellow-500' }}">
                                ● {{ strtoupper($booking->status) }}
                            </span>
                        </div>

                        @if ($booking->status === 'cancelled')
                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-center">
                                <p class="text-red-600 text-[10px] font-bold uppercase leading-tight">
                                    Booking Rejected
                                </p>
                                <p class="text-[9px] text-red-400 mt-1">
                                    Transaction ID verification failed. Please contact support.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Bottom Cutout Decoration --}}
                <div
                    class="h-2 w-full bg-[radial-gradient(circle,transparent_5px,#fff_5px)] bg-[length:15px_15px] absolute -bottom-1">
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="mt-8 flex flex-col gap-3 no-print px-2">
                <div class="grid grid-cols-2 gap-3">
                    {{-- SAVE AS IMAGE --}}
                    <button onclick="downloadTicketImage()"
                        class="flex items-center justify-center gap-2 bg-emerald-600 text-white font-bold py-4 rounded-xl hover:bg-emerald-500 transition shadow-lg text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Save Image
                    </button>

                    {{-- PRINT PDF --}}
                    <button onclick="window.print()"
                        class="flex items-center justify-center gap-2 bg-white text-black font-bold py-4 rounded-xl hover:bg-gray-100 transition shadow-lg text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print PDF
                    </button>
                </div>

                <a href="{{ route('dashboard') }}"
                    class="w-full py-4 text-gray-400 text-sm font-bold text-center hover:text-white transition">
                    ← Return to Dashboard
                </a>
                 <a href="{{ route('bookings.index') }}"
                    class="w-full py-4 text-gray-400 text-sm font-bold text-center hover:text-white transition">
                    ← Return to Booking
                </a>
            </div>
        </div>
    </div>

    {{-- SCRIPTS & STYLES --}}
    <script>
        function downloadTicketImage() {
            const ticket = document.getElementById('ticket-slip');

            // Configuration for the image capture
            html2canvas(ticket, {
                scale: 3, // High resolution
                backgroundColor: "#ffffff",
                useCORS: true, // Crucial for loading the QR code from external API
                logging: false
            }).then(canvas => {
                const image = canvas.toDataURL("image/png");
                const link = document.createElement('a');
                link.download = 'MovieTicket-{{ $booking->id }}.png';
                link.href = image;
                link.click();
            });
        }
    </script>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .min-h-screen {
                background: white !important;
                padding: 0 !important;
                display: block !important;
            }

            #ticket-slip {
                box-shadow: none !important;
                width: 100% !important;
                max-width: 100% !important;
                border: none !important;
            }
        }

        /* Styling the zig-zag effect */
        #ticket-slip::before,
        #ticket-slip::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            height: 10px;
            z-index: 10;
        }
    </style>
</x-app-layout>
