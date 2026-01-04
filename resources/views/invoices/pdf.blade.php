<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            background: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0d9488;
        }
        .company-info h1 {
            color: #0d9488;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .company-info p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            color: #0d9488;
            margin: 0;
            font-size: 24px;
        }
        .invoice-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .client-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .client-info h3 {
            color: #0d9488;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .client-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background: #0d9488;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
            vertical-align: top;
        }
        .items-table tr:nth-child(even) {
            background: #f9fafb;
        }
        .totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .totals-table {
            width: 300px;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }
        .totals-table .total-row {
            background: #0d9488;
            color: white;
            font-weight: bold;
        }
        .notes {
            margin-bottom: 30px;
        }
        .notes h3 {
            color: #0d9488;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .notes p {
            margin: 5px 0;
            font-size: 14px;
            line-height: 1.5;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-draft { background: #f3f4f6; color: #374151; }
        .status-sent { background: #dbeafe; color: #1e40af; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-overdue { background: #fee2e2; color: #991b1b; }
        .status-cancelled { background: #f3f4f6; color: #6b7280; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <h1>Solar ERP</h1>
            <p>Solar Panel Management System</p>
            <p>123 Business Park, Mumbai, Maharashtra 400001</p>
            <p>Phone: +91-9876543210 | Email: info@solarerp.com</p>
            <p>GST: 27ABCDE1234F1Z5</p>
        </div>
        <div class="invoice-info">
            <h2>INVOICE</h2>
            <p><strong>Invoice #:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>Date:</strong> {{ $invoice->invoice_date->format('M d, Y') }}</p>
            <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
            </p>
        </div>
    </div>

    <!-- Client Information -->
    <div class="client-info">
        <h3>Bill To:</h3>
        <p><strong>{{ $invoice->client->name }}</strong></p>
        @if($invoice->client->company)
        <p>{{ $invoice->client->company }}</p>
        @endif
        @if($invoice->client->address)
        <p>{{ $invoice->client->address }}</p>
        @endif
        <p>Phone: {{ $invoice->client->phone }}</p>
        @if($invoice->client->email)
        <p>Email: {{ $invoice->client->email }}</p>
        @endif
        @if($invoice->project)
        <p><strong>Project:</strong> {{ $invoice->project->name }} ({{ $invoice->project->project_code }})</p>
        @endif
    </div>

    <!-- Invoice Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: center;">Qty</th>
                <th style="text-align: right;">Rate</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <!-- Sample items - in real implementation, these would come from invoice items table -->
            <tr>
                <td>Solar Panel Installation - {{ $invoice->project->name ?? 'General Installation' }}</td>
                <td style="text-align: center;">1</td>
                <td style="text-align: right; white-space: nowrap;">Rs. {{ number_format($invoice->subtotal) }}</td>
                <td style="text-align: right; white-space: nowrap;">Rs. {{ number_format($invoice->subtotal) }}</td>
            </tr>
            @if($invoice->tax_amount > 0)
            <tr>
                <td>GST (18%)</td>
                <td style="text-align: center;">-</td>
                <td style="text-align: right; white-space: nowrap;">Rs. {{ number_format($invoice->tax_amount) }}</td>
                <td style="text-align: right; white-space: nowrap;">Rs. {{ number_format($invoice->tax_amount) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right; white-space: nowrap;">Rs. {{ number_format($invoice->subtotal) }}</td>
            </tr>
            @if($invoice->tax_amount > 0)
            <tr>
                <td>Tax:</td>
                <td style="text-align: right; white-space: nowrap;">Rs. {{ number_format($invoice->tax_amount) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td>Total Amount:</td>
                <td style="text-align: right; white-space: nowrap;">Rs. {{ number_format($invoice->total_amount) }}</td>
            </tr>
            @if($invoice->paid_amount > 0)
            <tr>
                <td>Paid Amount:</td>
                <td style="text-align: right; white-space: nowrap;">Rs. {{ number_format($invoice->paid_amount) }}</td>
            </tr>
            <tr>
                <td>Outstanding:</td>
                <td style="text-align: right; white-space: nowrap;">Rs. {{ number_format($invoice->total_amount - $invoice->paid_amount) }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Notes -->
    @if($invoice->notes)
    <div class="notes">
        <h3>Notes:</h3>
        <p>{{ $invoice->notes }}</p>
    </div>
    @endif

    <!-- Terms & Conditions -->
    @if($invoice->terms_conditions)
    <div class="notes">
        <h3>Terms & Conditions:</h3>
        <p>{{ $invoice->terms_conditions }}</p>
    </div>
    @else
    <div class="notes">
        <h3>Terms & Conditions:</h3>
        <p>Payment is due within {{ $invoice->due_date->diffInDays($invoice->invoice_date) }} days of invoice date. Late payments may incur additional charges.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for choosing Solar ERP for your solar energy needs!</p>
        <p>For any queries, please contact us at info@solarerp.com or call +91-9876543210</p>
        <p>This is a computer generated invoice and does not require signature.</p>
    </div>
</body>
</html>
