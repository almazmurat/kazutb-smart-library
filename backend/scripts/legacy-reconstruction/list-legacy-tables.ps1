param(
    [string]$Instance = "localhost\SQLEXPRESS",
    [string]$Database = "marc_restored"
)

$connectionString = "Server=$Instance;Database=$Database;Integrated Security=True;TrustServerCertificate=True;"
$connection = New-Object System.Data.SqlClient.SqlConnection $connectionString
$tables = @()

try {
    $connection.Open()
    $command = $connection.CreateCommand()
    $command.CommandText = @"
SELECT
    t.name AS table_name,
    SUM(CASE WHEN p.index_id IN (0, 1) THEN p.rows ELSE 0 END) AS row_count
FROM sys.tables t
LEFT JOIN sys.partitions p ON p.object_id = t.object_id
WHERE t.is_ms_shipped = 0
GROUP BY t.name
ORDER BY t.name
"@

    $reader = $command.ExecuteReader()
    try {
        while ($reader.Read()) {
            $tables += [ordered]@{
                table    = [string]$reader["table_name"]
                rowCount = [int64]$reader["row_count"]
            }
        }
    }
    finally {
        $reader.Close()
    }
}
finally {
    if ($connection.State -eq [System.Data.ConnectionState]::Open) {
        $connection.Close()
    }
}

$tables | ConvertTo-Json -Depth 4