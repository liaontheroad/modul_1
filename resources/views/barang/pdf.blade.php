<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Tag Harga</title>
    <style>
        @page { size: A4 portrait; margin: 10px 15px; }
        body { font-family: 'Helvetica', sans-serif; margin: 0; padding: 0; color: #333; }
        
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        
        td { 
            width: 20%; 
            height: 122px; 
            text-align: center; 
            vertical-align: middle; 
            padding: 3px; 
        }

        .tag-box {
            border: 1px dashed #ccc; 
            border-radius: 8px;
            padding: 8px;
            height: 90px;
            overflow: hidden;
        }
        
        .nama { font-weight: bold; font-size: 11px; margin-bottom: 5px; display: block; }
        .harga { font-size: 16px; color: #b66dff; font-weight: bold; margin-bottom: 3px; }
        .id { font-size: 9px; color: #888; }
    </style>
</head>
<body>

    <table>
        @foreach(array_chunk($dataCetak, 5) as $baris)
        <tr>
            @foreach($baris as $item)
            <td>
                @if($item != null)
                <div class="tag-box">
                    <span class="nama">{{ \Illuminate\Support\Str::limit($item->nama, 20) }}</span>
                    <div class="harga">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                    <div class="id">{{ $item->id_barang }}</div>
                </div>
                @endif
            </td>
            @endforeach
            
            @for($i = count($baris); $i < 5; $i++)
                <td></td>
            @endfor
        </tr>
        @endforeach
    </table>

</body>
</html>