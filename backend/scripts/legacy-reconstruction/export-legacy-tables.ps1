param(
    [string]$Instance = "localhost\SQLEXPRESS",
    [string]$Database = "marc_restored",
    [string]$OutputDir,
    [string]$Tables = "DOC,DOC_VIEW,INV,READERS,RDRBP,BOOKPOINTS,SIGLAS,POINTSIGLA,LIBS,LIBS_BOOKPOINTS,LIBS_SIGLAS,PUBLISHER,BOOKSTATES,PREORDERS"
)

if (-not $OutputDir) {
    Write-Error "OutputDir is required"
    exit 1
}

New-Item -ItemType Directory -Force -Path $OutputDir | Out-Null

$tableList = $Tables.Split(",") | ForEach-Object { $_.Trim() } | Where-Object { $_ }
$connectionString = "Server=$Instance;Database=$Database;Integrated Security=True;TrustServerCertificate=True;"
$connection = New-Object System.Data.SqlClient.SqlConnection $connectionString
$manifest = @()

try {
    $connection.Open()

    foreach ($tableName in $tableList) {
        $outputFile = Join-Path $OutputDir ($tableName.ToLower() + ".ndjson")
        $command = $connection.CreateCommand()
        $command.CommandText = "SELECT * FROM dbo.[$tableName]"
        $reader = $command.ExecuteReader()
        $writer = [System.IO.StreamWriter]::new($outputFile, $false, [System.Text.UTF8Encoding]::new($false))
        $rowCount = 0

        try {
            while ($reader.Read()) {
                $row = [ordered]@{}

                for ($i = 0; $i -lt $reader.FieldCount; $i++) {
                    $columnName = $reader.GetName($i)

                    if ($reader.IsDBNull($i)) {
                        $row[$columnName] = $null
                        continue
                    }

                    $value = $reader.GetValue($i)
                    if ($value -is [byte[]]) {
                        $row[$columnName] = [Convert]::ToBase64String($value)
                    }
                    else {
                        $row[$columnName] = $value
                    }
                }

                $writer.WriteLine(($row | ConvertTo-Json -Compress -Depth 8))
                $rowCount += 1
            }
        }
        finally {
            $writer.Dispose()
            $reader.Close()
        }

        $manifest += [ordered]@{
            table    = $tableName
            file     = [System.IO.Path]::GetFileName($outputFile)
            rowCount = $rowCount
        }
    }
}
finally {
    if ($connection.State -eq [System.Data.ConnectionState]::Open) {
        $connection.Close()
    }
}

$manifestPath = Join-Path $OutputDir "manifest.json"
$manifest | ConvertTo-Json -Depth 5 | Set-Content -Path $manifestPath -Encoding UTF8
Write-Output $manifestPath