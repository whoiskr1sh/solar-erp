<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation - {{ $quotation->quotation_number }}</title>
    <style>
        @page {
            margin: 5mm;
            size: A4;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', 'Arial', 'Helvetica', sans-serif;
            font-size: 9px;
            color: #000;
            line-height: 1.15;
        }
        .page-break {
            page-break-after: always;
        }
        /* Header Section */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
            padding-bottom: 4px;
        }
        .company-section {
            flex: 1;
        }
        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
        }
        .logo-circle {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
            overflow: hidden;
            position: relative;
        }
        .logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
            margin: 0;
        }
        .company-name-main {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: 0.5px;
        }
        .company-address {
            font-size: 9px;
            color: #1f2937;
            line-height: 1.4;
            margin-top: 4px;
        }
        .company-contact-info {
            font-size: 8.5px;
            color: #374151;
            margin-top: 4px;
            line-height: 1.5;
        }
        .company-contact-info strong {
            color: #1f2937;
        }
        .trusted-badge {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: white;
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 7px;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Quotation Banner */
        .quotation-banner {
            background: #1e40af;
            color: white;
            padding: 5px 0;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin: 5px 0;
            letter-spacing: 1px;
        }
        
        /* Quotation Info */
        .quotation-header-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 9px;
        }
        .quotation-header-info p {
            margin: 1px 0;
        }
        .guarantee-note {
            color: #dc2626;
            font-style: italic;
            font-size: 8px;
            margin-top: 2px;
        }
        
        /* Client Section */
        .client-to-section {
            margin-bottom: 4px;
        }
        .client-to-section h4 {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 1px;
            color: #1f2937;
        }
        .client-to-section p {
            font-size: 8px;
            margin: 1px 0;
            color: #374151;
        }
        
        /* Dealer Section */
        .dealer-info-box {
            background: #f3f4f6;
            padding: 4px 6px;
            margin-bottom: 4px;
            border-left: 4px solid #1e40af;
            border-radius: 3px;
        }
        .dealer-info-box h4 {
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 2px;
            color: #1e40af;
        }
        .dealer-info-box p {
            font-size: 7.5px;
            margin: 1px 0;
            color: #4b5563;
        }
        
        /* Subject Line */
        .subject-box {
            background: #eff6ff;
            padding: 4px 6px;
            margin-bottom: 4px;
            border-left: 4px solid #3b82f6;
            border-radius: 3px;
        }
        .subject-box strong {
            font-size: 8px;
            color: #1e40af;
        }
        
        /* Intro Message */
        .intro-text {
            font-size: 8px;
            margin-bottom: 5px;
            font-style: italic;
            color: #4b5563;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 7.5px;
        }
        .items-table th {
            background: #1e40af;
            color: white;
            padding: 3px 2px;
            text-align: center;
            border: 1px solid #1e3a8a;
            font-weight: bold;
            font-size: 7.5px;
        }
        .items-table td {
            padding: 2px 2px;
            border: 1px solid #d1d5db;
            text-align: center;
            font-size: 7.5px;
        }
        .items-table td:first-child,
        .items-table td:nth-child(2) {
            text-align: left;
            padding-left: 5px;
        }
        .items-table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        /* Subsidy Table */
        .subsidy-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 7.5px;
        }
        .subsidy-table th {
            background: #059669;
            color: white;
            padding: 3px 2px;
            text-align: center;
            border: 1px solid #047857;
            font-weight: bold;
            font-size: 7.5px;
        }
        .subsidy-table td {
            padding: 2px 2px;
            border: 1px solid #d1d5db;
            text-align: center;
            font-size: 7.5px;
        }
        .subsidy-table td:first-child,
        .subsidy-table td:nth-child(2) {
            text-align: left;
            padding-left: 5px;
        }
        
        /* Financial Summary */
        .financial-summary-box {
            background: #f9fafb;
            padding: 4px;
            border: 1px solid #d1d5db;
            margin-bottom: 5px;
            border-radius: 4px;
        }
        .financial-summary-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .financial-summary-box td {
            padding: 2px 5px;
            font-size: 8.5px;
        }
        .financial-summary-box td:first-child {
            font-weight: bold;
            width: 45%;
            color: #1f2937;
        }
        .financial-summary-box td:last-child {
            text-align: right;
            font-weight: bold;
            color: #1f2937;
        }
        .financial-summary-box tr:last-child {
            background: #dbeafe;
        }
        .financial-summary-box tr:last-child td {
            font-size: 9.5px;
            padding: 3px 5px;
        }
        
        /* Amount in Words */
        .amount-words-box {
            background: #fef3c7;
            padding: 4px 6px;
            margin-bottom: 4px;
            border-left: 4px solid #f59e0b;
            border-radius: 3px;
        }
        .amount-words-box strong {
            font-size: 8px;
            color: #92400e;
        }
        
        /* Promo Box */
        .promo-banner {
            background: #10b981;
            color: white;
            padding: 4px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 3px;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }
        
        /* Page Number */
        .page-number {
            position: absolute;
            bottom: 5mm;
            right: 5mm;
            font-size: 8px;
            color: #6b7280;
        }
        
        /* Terms Page */
        .terms-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px;
            padding-bottom: 3px;
        }
        .terms-section {
            margin-bottom: 6px;
        }
        .terms-section h3 {
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 2px;
        }
        .terms-section h4 {
            font-size: 9px;
            font-weight: bold;
            margin: 5px 0 3px 0;
            color: #059669;
        }
        .terms-section p {
            font-size: 8px;
            margin: 2px 0;
            line-height: 1.25;
            color: #374151;
        }
        .terms-section ul {
            margin-left: 16px;
            margin-top: 2px;
        }
        .terms-section li {
            font-size: 8px;
            margin: 1px 0;
            line-height: 1.25;
            color: #374151;
        }
        
        /* Bank Details */
        .bank-details-box {
            background: #eff6ff;
            padding: 5px;
            border: 1px solid #93c5fd;
            margin-bottom: 6px;
            border-radius: 4px;
        }
        .bank-details-box h4 {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #1e40af;
        }
        .bank-details-box p {
            font-size: 7.5px;
            margin: 2px 0;
            color: #1e40af;
        }
        
        /* BOS Table */
        .bos-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 7.5px;
        }
        .bos-table th {
            background: #059669;
            color: white;
            padding: 3px 2px;
            text-align: left;
            border: 1px solid #047857;
            font-weight: bold;
            font-size: 7.5px;
        }
        .bos-table td {
            padding: 2px 2px;
            border: 1px solid #d1d5db;
            font-size: 7.5px;
        }
        .bos-table tr:nth-child(even) {
            background: #f9fafb;
        }
        
        /* Footer Notes */
        .footer-notes {
            font-size: 7px;
            color: #6b7280;
            margin-top: 5px;
            font-style: italic;
            line-height: 1.25;
        }
        .footer-notes p {
            margin: 1px 0;
        }
        
        /* Signature */
        .signature-section {
            margin-top: 8px;
            text-align: right;
        }
        .signature-section p {
            font-size: 9px;
            margin: 2px 0;
            color: #1f2937;
        }
        .signature-section p:first-child {
            font-weight: bold;
        }
        
        /* Partner Logos Section */
        .partner-logos {
            margin-top: 6px;
            margin-bottom: 3px;
            padding: 4px 0;
        }
        .logos-row {
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 3px;
            flex-wrap: wrap;
        }
        .logo-item {
            text-align: center;
            padding: 2px;
            flex: 0 0 auto;
            min-width: 60px;
            max-width: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .logo-item img {
            max-width: 100%;
            max-height: 25px;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        .logo-item-text {
            font-size: 6px;
            color: #1f2937;
            font-weight: 500;
            line-height: 1.1;
            margin-top: 2px;
        }
        .logo-item-text strong {
            display: block;
            font-size: 6.5px;
            font-weight: bold;
        }
        .logo-item-small {
            font-size: 5px;
            color: #6b7280;
            display: block;
        }
    </style>
</head>
<body>
    <!-- Page 1: Quotation -->
    <div class="header-container">
        <div class="company-section">
            <div class="logo-container">
                <div class="logo-circle">
                    @php
                        $logoBase64 = null;
                        if (file_exists(public_path('logos/logo.png'))) {
                            $logoData = file_get_contents(public_path('logos/logo.png'));
                            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                        } elseif (file_exists(public_path('logos/logo.jpg'))) {
                            $logoData = file_get_contents(public_path('logos/logo.jpg'));
                            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($logoData);
                        } elseif (file_exists(public_path('logos/company-logo.png'))) {
                            $logoData = file_get_contents(public_path('logos/company-logo.png'));
                            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                        } elseif (file_exists(public_path('logos/company-logo.jpg'))) {
                            $logoData = file_get_contents(public_path('logos/company-logo.jpg'));
                            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($logoData);
                        }
                    @endphp
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Company Logo">
                    @else
                        <span style="color: #1e40af; font-weight: bold; font-size: 16px;">GC</span>
                    @endif
                </div>
                <div>
                    <div class="company-name-main">GREENCLEAN POWER SOLUTION</div>
                </div>
            </div>
            <div class="company-address">
                FF-54 SIDDHESHWAR HALLMARK, AJWA ROAD, VADODARA-390019 (GUJ)
            </div>
            <div class="company-contact-info">
                <strong>CONTACT NO:-</strong> 757-4031-782<br>
                <strong>Website:-</strong> www.greencleanpower.in<br>
                <strong>GST NO:-</strong> 24AAVFG4170G1Z8<br>
                <strong>Email id:-</strong> info@greencleanpower.in
            </div>
        </div>
        <div style="flex: 0 0 180px; text-align: right;">
            <div class="trusted-badge">
                TRUSTED SOLAR EPC COMPANY<br>GUJARAT & UP
    </div>
        </div>
    </div>

    <div class="quotation-banner">QUOTATION</div>

    <div class="quotation-header-info">
        <div>
            <p><strong>Quotation No.:</strong> {{ $quotation->quotation_number }}</p>
            <p><strong>Date:</strong> {{ $quotation->quotation_date->format('d/m/Y') }}</p>
            <p class="guarantee-note">*15 days Guaranteed Installation*</p>
        </div>
    </div>

    <div class="client-to-section">
        <h4>To {{ $quotation->client->name }}</h4>
        <p><strong>Add.:-</strong> {{ $quotation->client->address ?? 'N/A' }}{{ $quotation->client->city ? ', ' . $quotation->client->city : '' }}{{ $quotation->client->state ? ', ' . $quotation->client->state : '' }}{{ $quotation->client->pincode ? ', ' . $quotation->client->pincode : '' }}</p>
    </div>

    @if($quotation->channelPartner)
    <div class="dealer-info-box">
        <h4>AUTHORIZED DEALER: {{ $quotation->channelPartner->company_name }}</h4>
        <p><strong>CONTACT PERSON:</strong> {{ $quotation->channelPartner->contact_person }}</p>
        <p><strong>CONTACT NO.:</strong> {{ $quotation->channelPartner->phone }}</p>
    </div>
    @endif

    <div class="subject-box">
        <strong>Subject:-</strong> Quotation for On-Grid Solar Rooftop System{{ $quotation->quotation_type == 'subsidy_quotation' ? ' Under National Portal Scheme-23-24' : '' }}.
    </div>

    <div class="intro-text">
        Dear Sir, We acknowledge with thanks to receipt your inquiry; we are very pleased to offer you our best prices as per standard rates:
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 4%;">Sr No.</th>
                <th style="width: 42%;">Description of Work</th>
                <th style="width: 8%;">HSN Code</th>
                <th style="width: 6%;">Unit</th>
                <th style="width: 6%;">Qty</th>
                <th style="width: 9%;">Rate</th>
                <th style="width: 9%;">Taxable Amount</th>
                <th style="width: 5%;">Tax</th>
                <th style="width: 10%;">Tax Amount</th>
                <th style="width: 10%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Calculate system capacity from total amount (approximate)
                $systemCapacity = round($quotation->total_amount / 45000, 2); // Approximate rate per KW
                $qty = max(1, $systemCapacity);
                $rate = $quotation->subtotal / $qty;
                $taxable = $quotation->subtotal;
                $taxRate = $quotation->subtotal > 0 ? ($quotation->tax_amount / $quotation->subtotal) * 100 : 18;
                $taxAmount = $quotation->tax_amount;
                $total = $quotation->total_amount;
            @endphp
            <tr>
                <td>1</td>
                <td style="text-align: left;">{{ number_format($systemCapacity, 2) }} Kwp/SRT- Design, Supply, Installation, Testing & Commissioning of Grid Connected Rooftop Solar Photovoltaic System Including all Charges</td>
                <td>854140</td>
                <td>KW</td>
                <td>{{ number_format($qty, 2) }}</td>
                <td>{{ number_format($rate, 2) }}</td>
                <td>{{ number_format($taxable, 2) }}</td>
                <td>{{ number_format($taxRate, 2) }}%</td>
                <td>{{ number_format($taxAmount, 2) }}</td>
                <td>{{ number_format($total, 2) }}</td>
            </tr>
            @if($quotation->quotation_type == 'subsidy_quotation')
            <tr>
                <td>2</td>
                <td style="text-align: left;">Meter Charges*</td>
                <td></td>
                <td>Phase</td>
                <td>1.00</td>
                <td>2500.00</td>
                <td>2500.00</td>
                <td>18.0%</td>
                <td>450.00</td>
                <td>2950.00</td>
            </tr>
            @endif
        </tbody>
    </table>

    @if($quotation->quotation_type == 'subsidy_quotation')
    <!-- Subsidy Table -->
    <table class="subsidy-table">
        <thead>
            <tr>
                <th style="width: 8%;">Sr No.</th>
                <th style="width: 50%;">Charge Name</th>
                <th style="width: 21%;">Charge Subsidy</th>
                <th style="width: 21%;">Charge Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td style="text-align: left;">Subsidy (1Kw to 2Kw)</td>
                <td>Rs. 60,000.00</td>
                <td>78000.00</td>
            </tr>
            <tr>
                <td>2</td>
                <td style="text-align: left;">Subsidy (Upto 3 & Above 3Kw)</td>
                <td>Rs. 18,000.00</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Financial Summary -->
    <div class="financial-summary-box">
        <table>
            <tr>
                <td>After Subsidy Cost:</td>
                <td>Rs. {{ number_format($quotation->total_amount - ($quotation->quotation_type == 'subsidy_quotation' ? 78000 : 0), 2) }}</td>
            </tr>
            <tr>
                <td>Total System Amount:</td>
                <td>Rs. {{ number_format($quotation->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Discount / Round Off:</td>
                <td>Rs. {{ number_format(0, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Payable Amount:</strong></td>
                <td><strong>Rs. {{ number_format($quotation->total_amount, 2) }}</strong></td>
            </tr>
            <tr>
                <td>Currency:</td>
                <td>INR</td>
            </tr>
        </table>
    </div>

    <!-- Amount in Words -->
    <div class="amount-words-box">
        <strong>Amount in Words :-</strong> {{ $quotation->numberToWords($quotation->total_amount) }} Rupees Only.
    </div>

    <div class="promo-banner">EASY FINANCE AVAILIABLE</div>

    <!-- Partner/Brand Logos -->
    <div class="partner-logos">
        <div class="logos-row">
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/adani-solar.png')) || file_exists(public_path('logos/partners/adani-solar.jpg')))
                    <img src="{{ public_path('logos/partners/adani-solar.' . (file_exists(public_path('logos/partners/adani-solar.png')) ? 'png' : 'jpg')) }}" alt="Adani Solar">
                @else
                    <div class="logo-item-text">
                        <strong>adani</strong>
                        <span class="logo-item-small">Solar</span>
                    </div>
                @endif
            </div>
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/pahal-solar.png')) || file_exists(public_path('logos/partners/pahal-solar.jpg')))
                    <img src="{{ public_path('logos/partners/pahal-solar.' . (file_exists(public_path('logos/partners/pahal-solar.png')) ? 'png' : 'jpg')) }}" alt="PAHAL SOLAR">
                @else
                    <div class="logo-item-text">
                        <strong>PAHAL</strong>
                        <span class="logo-item-small">SOLAR</span>
                    </div>
                @endif
            </div>
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/rayzon-solar.png')) || file_exists(public_path('logos/partners/rayzon-solar.jpg')))
                    <img src="{{ public_path('logos/partners/rayzon-solar.' . (file_exists(public_path('logos/partners/rayzon-solar.png')) ? 'png' : 'jpg')) }}" alt="RAYZON SOLAR">
                @else
                    <div class="logo-item-text">
                        <strong>RAYZON</strong>
                        <span class="logo-item-small">SOLAR</span>
                    </div>
                @endif
            </div>
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/waaree.png')) || file_exists(public_path('logos/partners/waaree.jpg')))
                    <img src="{{ public_path('logos/partners/waaree.' . (file_exists(public_path('logos/partners/waaree.png')) ? 'png' : 'jpg')) }}" alt="WAAREE One with the Sun">
                @else
                    <div class="logo-item-text">
                        <strong>WAAREE</strong>
                        <span class="logo-item-small">One with the Sun</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="logos-row">
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/deye.png')) || file_exists(public_path('logos/partners/deye.jpg')))
                    <img src="{{ public_path('logos/partners/deye.' . (file_exists(public_path('logos/partners/deye.png')) ? 'png' : 'jpg')) }}" alt="Deye">
                @else
                    <div class="logo-item-text">
                        <strong>Deye</strong>
                    </div>
                @endif
            </div>
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/havells.png')) || file_exists(public_path('logos/partners/havells.jpg')))
                    <img src="{{ public_path('logos/partners/havells.' . (file_exists(public_path('logos/partners/havells.png')) ? 'png' : 'jpg')) }}" alt="HAVELLS">
                @else
                    <div class="logo-item-text">
                        <strong>HAVELLS</strong>
                    </div>
                @endif
            </div>
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/xwatt.png')) || file_exists(public_path('logos/partners/xwatt.jpg')))
                    <img src="{{ public_path('logos/partners/xwatt.' . (file_exists(public_path('logos/partners/xwatt.png')) ? 'png' : 'jpg')) }}" alt="Xwätt SOLAR INVERTER">
                @else
                    <div class="logo-item-text">
                        <strong>Xwätt</strong>
                        <span class="logo-item-small">SOLAR INVERTER</span>
                    </div>
                @endif
            </div>
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/rr-kabel.png')) || file_exists(public_path('logos/partners/rr-kabel.jpg')))
                    <img src="{{ public_path('logos/partners/rr-kabel.' . (file_exists(public_path('logos/partners/rr-kabel.png')) ? 'png' : 'jpg')) }}" alt="RR KABEL">
                @else
                    <div class="logo-item-text">
                        <strong>RR KABEL</strong>
                    </div>
                @endif
            </div>
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/polycab.png')) || file_exists(public_path('logos/partners/polycab.jpg')))
                    <img src="{{ public_path('logos/partners/polycab.' . (file_exists(public_path('logos/partners/polycab.png')) ? 'png' : 'jpg')) }}" alt="POLYCAB">
                @else
                    <div class="logo-item-text">
                        <strong>POLYCAB</strong>
                    </div>
                @endif
            </div>
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/vsole.png')) || file_exists(public_path('logos/partners/vsole.jpg')))
                    <img src="{{ public_path('logos/partners/vsole.' . (file_exists(public_path('logos/partners/vsole.png')) ? 'png' : 'jpg')) }}" alt="VSOLE">
                @else
                    <div class="logo-item-text">
                        <strong>VSOLE</strong>
                    </div>
                @endif
            </div>
            <div class="logo-item">
                @if(file_exists(public_path('logos/partners/elmex.png')) || file_exists(public_path('logos/partners/elmex.jpg')))
                    <img src="{{ public_path('logos/partners/elmex.' . (file_exists(public_path('logos/partners/elmex.png')) ? 'png' : 'jpg')) }}" alt="elmex">
                @else
                    <div class="logo-item-text">
                        <strong>elmex</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="page-number">1</div>

    <!-- Page 2: Terms & Conditions -->
    <div class="page-break"></div>
    
    <div class="terms-header">
        <div class="company-section">
            <div class="logo-container">
                <div class="logo-circle">
                    @php
                        $logoBase64 = null;
                        if (file_exists(public_path('logos/logo.png'))) {
                            $logoData = file_get_contents(public_path('logos/logo.png'));
                            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                        } elseif (file_exists(public_path('logos/logo.jpg'))) {
                            $logoData = file_get_contents(public_path('logos/logo.jpg'));
                            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($logoData);
                        } elseif (file_exists(public_path('logos/company-logo.png'))) {
                            $logoData = file_get_contents(public_path('logos/company-logo.png'));
                            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
                        } elseif (file_exists(public_path('logos/company-logo.jpg'))) {
                            $logoData = file_get_contents(public_path('logos/company-logo.jpg'));
                            $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($logoData);
                        }
                    @endphp
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Company Logo">
                    @else
                        <span style="color: #1e40af; font-weight: bold; font-size: 16px;">GC</span>
            @endif
                </div>
                <div>
                    <div class="company-name-main">GREENCLEAN POWER SOLUTION</div>
                </div>
            </div>
        </div>
        <div style="flex: 0 0 180px; text-align: right;">
            <div class="trusted-badge">
                TRUSTED SOLAR EPC COMPANY<br>GUJARAT & UP
            </div>
        </div>
    </div>

    <div class="terms-section">
        <h3>Terms & Conditions</h3>
        
        <h4>1. GST</h4>
        <p>GST is inclusive in the quoted rate for the Solar Rooftop system.</p>

        <h4>2. Scope of Work</h4>
        <p>The complete SPV (Solar Photovoltaic) system includes Site survey, Design, Supply, Transportation, Installation & Commissioning.</p>

        <h4>3. Execution Period</h4>
        <p>85 days execution period starting from the date of payment of F.Q. charges and connectivity charges to DISCOM.</p>

        <h4>4. Others:</h4>
        <ul>
            <li>Rates are valid for 7 days from the date of quotation.</li>
            <li>Any differences in Meter / Government Charges will be borne by the client as actual.</li>
            <li>Extra cable beyond the Bill of Materials (BOM) will incur additional charges.</li>
            <li>Premium charges will apply for Top-con panels.</li>
        </ul>
    </div>

    <div class="terms-section">
        <h3>Benefits</h3>
        <ul>
            <li><strong>1) 30 year's Solar panel performance warranty*</strong></li>
            <li><strong>2) 12 Year's Solar panel manufacturing warranty*</strong></li>
            <li><strong>3) 8 Year's On-Grid Inverter on-site warranty*</strong></li>
            <li><strong>4) 6 Year's Comprehensive maintenance of solar system.</strong></li>
            <li><strong>5) Wifi-stick/GPRS for data monitoring system.</strong></li>
        </ul>
        <p style="margin-top: 5px; font-size: 8px;">
            <strong>Monthly Cumulative Solar Generation:</strong> 486 Units.<br>
            <strong>Monthly 3888 INR bill</strong> can be easily cut down to zero.<br>
            <strong>Payback Period:</strong> 2 to 3 Years*.<br>
            <strong>Free Electricity for:</strong> 27 to 28 Years.
        </p>
    </div>

    <div class="terms-section">
        <h3>Payment Terms & Bank Details</h3>
        <div class="bank-details-box">
            <h4>1) Advance at the time of application:</h4>
            <p>20000/- INR (NON-REFUNDABLE)</p>

            <h4>2) At the time of (Pre-Dispatch Inspection) of solar system:</h4>
            <p>90% (of Balance Payment)</p>

            <h4>3) Before Signing of Net Meter Agreement:</h4>
            <p>100% (of Balance Payment)</p>

            <h4>4) All payment should be done in favour of "Greenclean Power Solution" only.</h4>
            
            <p style="margin-top: 5px; font-size: 7.5px;">
                <strong>Company Name:</strong> Greenclean Power Solution<br>
                <strong>Bank Name:</strong> ICICI BANK<br>
                <strong>Account No.:</strong> 777705170720<br>
                <strong>IFSC:</strong> ICIC0007619<br>
                <strong>Branch:</strong> SAYAJIPURA
            </p>
        </div>
    </div>

    <div class="terms-section">
        <h3>B.O.S (Bill of Supply/Materials)</h3>
        <table class="bos-table">
            <thead>
                <tr>
                    <th style="width: 5%;">Sr</th>
                    <th style="width: 60%;">Description</th>
                    <th style="width: 35%;">Brands / Specifications</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.</td>
                    <td>Solar PV Modules (540 to 550 Wp Mono-Perc Bifacial)</td>
                    <td>ADANI / PAHAL / RAYZON / WAAREE</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Solar Grid-tie Inverter</td>
                    <td>DEYE / X-WATT / VSOLE / HAVELLS / WAAREE</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Module Mounting Structure</td>
                    <td>HDGI Square Pipe (60 Micron) (60x40x2 & 40x40x2) Box Pipe OR C-Channel. Structure Height: (6S X 9N) Feet.</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Cable (AC & DC)</td>
                    <td>Polycab / Jhonson / Waacab. Upto (20 & 50) Mtr.</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>Cable (Earthing & LA)</td>
                    <td>Polycab / Genome / Jhonson / Waacab. Upto (50 & 30) Mtr.</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>ACDB & DCDB</td>
                    <td>As per design. (SET)</td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td>Misc Material (Conduite pipe, Cable tie, Anchor fastner, MC4 Connector, Earthing Rod, Lightning Arrester & ETC.)</td>
                    <td>ISI Approved. As per requirement.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer-notes">
        <p>Any Disputes are Subject to Vadodara Jurisdiction.</p>
        <p>* Any differences will be paid as actual by consumer</p>
        <p>* Payback @ rate of INR 8 per unit if project delayed from the given timeline</p>
        <p>*As respective to OEM warranty terms</p>
        <p>All payback is respective to the payment terms of "Greenclean Power Solution"</p>
    </div>

    <div class="signature-section">
        <p><strong>Your's Sincerely</strong></p>
        <p><strong>GreenClean Power Solution</strong></p>
    </div>

    <div class="page-number">2</div>
</body>
</html>
