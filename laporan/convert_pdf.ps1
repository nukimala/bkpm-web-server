$folder = "C:\laragon\www\webserver\laporan"
$word = New-Object -ComObject Word.Application
$word.Visible = $false
$word.DisplayAlerts = 0

1..9 | ForEach-Object {
    $num = $_
    $docxPath = Join-Path $folder "template-laporan-acara$num.docx"
    $pdfPath = Join-Path $folder "laporan acara $num.pdf"
    
    if (Test-Path $docxPath) {
        Write-Output "Converting: template-laporan-acara$num.docx -> laporan acara $num.pdf ..."
        try {
            $doc = $word.Documents.Open($docxPath, $false, $true)
            $doc.ExportAsFixedFormat($pdfPath, 17)
            $doc.Close([ref]$false)
            Write-Output "Success: laporan acara $num.pdf"
        } catch {
            Write-Error "Error converting acara $num : $_"
        }
    } else {
        Write-Output "Skipping acara $num (file not found: template-laporan-acara$num.docx)"
    }
}

$word.Quit()
[System.Runtime.Interopservices.Marshal]::ReleaseComObject($word) | Out-Null
[System.GC]::Collect()
[System.GC]::WaitForPendingFinalizers()
Write-Output "Finished processing all files."
