function Convert-ToSeoFriendly {
    param([string]$text)
    
    # Türkçe karakterleri değiştir (case-sensitive)
    $text = $text -replace 'İ', 'i' -replace 'I', 'i'
    $text = $text -replace 'Ş', 's' -replace 's', 's'
    $text = $text -replace 'Ğ', 'g' -replace 'g', 'g'
    $text = $text -replace 'Ü', 'u' -replace 'u', 'u'
    $text = $text -replace 'Ö', 'o' -replace 'o', 'o'
    $text = $text -replace 'Ç', 'c' -replace 'c', 'c'
    $text = $text -replace 'ı', 'i'
    
    # Küçük harfe çevir
    $text = $text.ToLower()
    
    # Boşlukları tire yap
    $text = $text -replace '\s+', '-'
    
    # Özel karakterleri değiştir/kaldır
    $text = $text -replace '&', '-and-'
    $text = $text -replace '[^a-z0-9\.\-]', '-'
    
    # Çift tireleri tek yap
    while ($text -match '--') {
        $text = $text -replace '--', '-'
    }
    
    # Başta ve sondaki tireleri kaldır
    $text = $text.Trim('-')
    
    return $text
}

Write-Host "=== Renaming all files to SEO-friendly names ===`n"

Write-Host "Hero images:"
$heroFiles = Get-ChildItem -Path "public\services\hero-images" -File
foreach ($file in $heroFiles) {
    $extension = $file.Extension
    $nameWithoutExt = [System.IO.Path]::GetFileNameWithoutExtension($file.Name)
    $newName = (Convert-ToSeoFriendly $nameWithoutExt) + $extension
    
    if ($newName -ne $file.Name) {
        Rename-Item -Path $file.FullName -NewName $newName -Force
        Write-Host "  ✓ $($file.Name) -> $newName"
    }
}

Write-Host "`nIcon SVGs:"
$iconFiles = Get-ChildItem -Path "public\services\icons" -File
foreach ($file in $iconFiles) {
    $extension = $file.Extension
    $nameWithoutExt = [System.IO.Path]::GetFileNameWithoutExtension($file.Name)
    $newName = (Convert-ToSeoFriendly $nameWithoutExt) + $extension
    
    if ($newName -ne $file.Name) {
        Rename-Item -Path $file.FullName -NewName $newName -Force
        Write-Host "  ✓ $($file.Name) -> $newName"
    }
}

Write-Host "`n=== Done! All files renamed to SEO-friendly format ===`n"
Write-Host "Sample filenames:"
Get-ChildItem "public\services\hero-images" | Select-Object -First 5 | ForEach-Object { 
    Write-Host "  - $($_.Name)" 
}
