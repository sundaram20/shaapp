<?php include_once("../config/auto_loader.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Print Cash Memo - DOC-2026-001</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <style>
        .erp-receipt-box {
            background: white; width: 210mm; margin: 0 auto; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            font-family: 'Arial', sans-serif; font-size: 11px; color: #000;
            padding: 10mm; box-sizing: border-box;
            display: flex; flex-direction: column; min-height: 177mm;
        }
        .erp-table { width: 100%; border-collapse: collapse; }
        .erp-table th, .erp-table td { border: 1px solid #000; padding: 6px 8px; vertical-align: middle; }
        .b-box { border: 1px solid #000; }
        .b-r { border-right: 1px solid #000; }
        .b-b { border-bottom: 1px solid #000; }
        .b-t { border-top: 1px solid #000; }
        .font-bold { font-weight: 700; }
        .uppercase { text-transform: uppercase; }

        @media print {
            @page { size: A4; margin: 0mm !important; }
            html, body { margin: 0 !important; padding: 0 !important; background: white !important; }
            .no-print { display: none !important; }
            .erp-receipt-box { 
                box-shadow: none !important; margin: 0 !important; 
                padding: 10mm !important; width: 100% !important; 
                height: 297mm !important;
            }
        }
    </style>
</head>
<body class="p-4 bg-gray-100 min-h-screen print:p-0 print:min-h-0 print:bg-white">

    <div class="max-w-[210mm] mx-auto mb-4 flex justify-end no-print">
        <button onclick="window.print()" class="bg-gray-800 text-white px-6 py-2 rounded font-bold text-xs uppercase tracking-widest cursor-pointer hover:bg-gray-700">Print Memo</button>
        &nbsp;&nbsp;
       <button onclick="window.location.href='/app/cashier/manageCashMemo.php'" class="bg-gray-200 text-gray-800 px-6 py-2 rounded font-bold text-xs uppercase tracking-widest cursor-pointer hover:bg-gray-300">
    Close
</button>
    </div>

    <div id="printable-area" class="erp-receipt-box">
        <div class="b-box flex-grow flex flex-col">
            
            <div class="flex justify-between items-center p-4 b-b">
                <div class="w-1/4"></div>
                <div class="w-2/4 text-center">
                    <h1 class="text-2xl font-bold uppercase tracking-tighter">RoomStatusHUB</h1>
                    <p class="text-[10px] leading-tight">
                        New Delhi Area, India<br>
                        Phone: +91 11 2345 6789 <br>
                        <strong>Email: billing@roomstatushub.com</strong>
                    </p>
                </div>
                <div class="w-1/4 text-right">
                    <div class="text-xs font-bold uppercase underline">Cash Memo Receipt</div>
                </div>
            </div>

            <div class="b-b bg-gray-50 flex text-[10px]">
                <div class="w-1/3 b-r p-2"><strong>Document No:</strong> <br> DOC-2026-001</div>
                <div class="w-1/3 b-r p-2"><strong>Date:</strong> <br> 10-06-2026</div>
                <div class="w-1/3 p-2"><strong>Cashier Name:</strong> <br> Vansh</div>
            </div>

            <div class="b-b flex text-[11px]">
                <div class="w-full p-2">
                    <!-- <div class="text-[9px] uppercase text-gray-500 font-bold mb-1">Account Holder / Client Description</div>
                    <div class="font-bold text-sm">Counter Sales Profile</div> -->
                </div>
            </div>

            <div class="flex-grow">
                <table class="erp-table">
                    <thead class="bg-gray-100 uppercase text-[9px]">
                        <tr>
                            <th class="w-[8%] text-center">S.No</th>
                            <th class="w-[30%] text-left">Ledger Account</th>
                            <th class="w-[20%] text-center">Payment Mode</th>
                            <th class="w-[25%] text-left">Remarks</th>
                            <th class="w-[17%] text-right">Amount (INR)</th>
                        </tr>
                    </thead>
                    <tbody class="text-[11px]">
                        <tr>
                            <td class="text-center">1</td>
                            <td class="font-bold">Cash Account</td>
                            <td class="text-center uppercase font-bold">Cash</td>
                            <td>Counter Sales Split</td>
                            <td class="text-right font-bold">1,000.00</td>
                        </tr>
                        <tr>
                            <td class="text-center">2</td>
                            <td class="font-bold">Bank Account</td>
                            <td class="text-center uppercase font-bold">Online/UPI</td>
                            <td>E-Commerce Advanced Tracking</td>
                            <td class="text-right font-bold">500.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-auto b-t flex">
                <div class="w-3/5 b-r p-3 flex flex-col justify-center">
                    <div class="italic text-[10px]">
                        <strong>Amount in words:</strong> <br>
                        One Thousand Five Hundred Indian Rupees Only.
                    </div>
                </div>
                <div class="w-2/5 flex flex-col justify-end">
                    <div class="flex justify-between p-4 bg-gray-100 text-sm h-full items-center">
                        <span class="font-bold uppercase tracking-tighter">Total Received:</span>
                        <span class="font-bold text-lg">₹ 1,500.00</span>
                    </div>
                </div>
            </div>

            <div class="text-center b-t py-3 text-[10px] uppercase bg-gray-50 font-medium">
                This is a system-generated receipt and does not require a physical signature.
            </div>
        </div>
    </div>
</body>
</html>