$repo = "https://github.com/hermanedward009-rgb/block-l-residents-portal"
$url = "https://dashboard.render.com/blueprint/new?repo=$repo"
Write-Host "Opening Render Blueprint for: $repo"
Write-Host "After deploy: set APP_URL to your *.onrender.com URL and redeploy."
Start-Process $url