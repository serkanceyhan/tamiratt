$files = Get-ChildItem -Path "public\services\hero-images" -File
$files += Get-ChildItem -Path "public\services\icons" -File

foreach ($file in $files) {
    $newName = $file.Name
    # Türkçe karakterleri değiştir
    $newName = $newName -replace 'ş','s' -replace 'Ş','S'
    $newName = $newName -replace 'ğ','g' -replace 'Ğ','G'
    $newName = $newName -replace 'ü','u' -replace 'Ü','U'
    $newName = $newName -replace 'ö','o' -replace 'Ö','O'
    $newName = $newName -replace 'ç','c' -replace 'Ç','C'
    $newName = $newName -replace 'ı','i' -replace 'İ','I'
    # Boşlukları tire yap
    $newName = $newName -replace ' ','-'
    # & işaretini kaldır
    $newName = $newName -replace '&',''
    # Çift tireleri tek yap
    $newName = $newName -replace '--','-'
    
    if ($newName -ne $file.Name) {
        $newPath = Join-Path $file.DirectoryName $newName
        Rename-Item -Path $file.FullName -NewName $newName -Force
        Write-Host "Renamed: $($file.Name) -> $newName"
    }
}
Write-Host "Done!"
