<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Agora-Inventory - Master Data Report</title>
    <style>
        @font-face {
            font-family: 'NikoshBAN';
            src: url('{{ public_path('admin_assets/fonts/NikoshBAN.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'NikoshBAN', sans-serif;
        }

        .custom-select option {
            color: rgb(93, 93, 134);
        }

        .mushak-header-top-right, .mushak-header-top-center {
            font-weight: bold;
            font-size: 16px;
            font-family: 'NikoshBAN';
        }

        .mushak-body {
            font-weight: normal;
            font-size: 15px;
            font-family: 'NikoshBAN';
        }

        .mushak-footer {
            font-size: 16px;
            line-height: 1.5;
            font-weight: bold;
            font-family: 'NikoshBAN';
            margin-top: 20px;
        }

        h1, h2, h7 {
            margin: 0;
            padding: 0;
        }

        .mushak-header-top-center h1, .mushak-header-top-center h2, .mushak-header-top-center h7 {
            margin-bottom: 5px;
        }

        .mushak-title h7 {
            font-weight: bold;
            font-size: 16px;
            font-family: 'NikoshBAN';
            margin: 0;
            padding: 0;
            line-height: 0.1;
            display: block;
        }

        .vendor-info {
            margin-bottom: 20px; /* Adjust as needed to ensure enough space between vendor blocks */
        }
    </style>
</head>
<body>
    <div class="row">
        <div class="container-fluid">
            <div class="row">
                <div class="col text-right mushak-header-top-right" style="text-align: right;">
                    <h7 class="mb-0">Master Data</h7>
                </div>
            </div>
            <!-- Page Heading -->
            <div class="text-center mushak-header-top-center" style="text-align: center;">
                <h7>People's Republic Of Bangladesh</h7>
                <h1>Agora Limited</h1>
                <h2>05, Mohakhali (5th Floor) Paragon Building, Dhaka-1212</h2>
                <h2>Agora - Inventory</h2>
            </div>
            <br>
            <!-- DataTales Example -->
            <div class="table-responsive mb-4 mt-4 mushak-body">
                <table class="table table-bordered normal-text-table" width="100%" cellspacing="0" style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #000; text-align: center;">SL No</th>
                            <th style="border: 1px solid #000; text-align: center;">Outlet Code</th>
                            <th style="border: 1px solid #000; text-align: center;">Location</th>
                            <th style="border: 1px solid #000; text-align: center;">MCH Code</th>
                            <th style="border: 1px solid #000; text-align: center;">Article No</th>
                            <th style="border: 1px solid #000; text-align: center;">Article Description</th>
                            <th style="border: 1px solid #000; text-align: center;">Stock Quantity</th>
                            <th style="border: 1px solid #000; text-align: center;">UOM</th>
                            <th style="border: 1px solid #000; text-align: center;">EANNO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($masterData as $data)
                        <tr>
                            <td style="border: 1px solid #000;">{{ $data->id }}</td>
                            <td style="border: 1px solid #000;">{{ $data->outlet_code }}</td>
                            <td style="border: 1px solid #000;">{{ $data->location }}</td>
                            <td style="border: 1px solid #000;">{{ $data->mch_code }}</td>
                            <td style="border: 1px solid #000;">{{ $data->article_no }}</td>
                            <td style="border: 1px solid #000;">{{ $data->article_description }}</td>
                            <td style="border: 1px solid #000;">{{ $data->stock_quantity }}</td>
                            <td style="border: 1px solid #000;">{{ $data->uom }}</td>
                            <td style="border: 1px solid #000;">{{ $data->eanno }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
