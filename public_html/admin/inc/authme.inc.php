<?php 
  defined("ADMIN") ? null: die('Hacking?');
  ($user["uye_rutbe"]) == 1 ? null: go(ADMIN_URL);
?>
<script src="<?=ADMIN_URL?>/assets/js/plugins/codemirror/codemirror.js"></script>
<link rel="stylesheet" href="<?=ADMIN_URL?>/assets/js/plugins/codemirror/codemirror.css">
<link rel="stylesheet" href="<?=ADMIN_URL?>/assets/js/plugins/codemirror/solarized.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.26.0/mode/yaml/yaml.min.js"></script>
<div class="block block-bordered">
  <div class="block-header bg-gray-lighter">
      <h3 class="block-title">Authme ConfIg Oluşturucu</h3>
  </div>
<textarea id="code" class="form-control">DataSource:
  mySQLColumnName: uye_kadi
  mySQLTablename: uyeler
  mySQLUsername: <?=$_CONFIG["MYSQL_USER"]?>

  backend: MySQL
  mySQLColumnLastLogin: son_giris
  mySQLDatabase: <?=$_CONFIG["MYSQL_DB"]?>

  mySQLPort: '<?=$_CONFIG["MYSQL_POST"]?>'
  mySQLColumnIp: uye_ip
  mySQLHost: <?=$_CONFIG["MYSQL_HOST"]?>

  mySQLColumnPassword: uye_sifre
  mySQLPassword: '<?=$_CONFIG["MYSQL_PASS"]?>'
  caching: false
  mySQLlastlocX: x
  mySQLlastlocY: y
  mySQLlastlocZ: z
  mySQLlastlocWorld: world
  mySQLColumnEmail: uye_email
  mySQLColumnId: uye_id
  mySQLColumnIdnLogged: uye_oyunda
GroupOptions:
UnregisteredPlayerGroup: ''
RegisteredPlayerGroup: ''
Permissions:
  PermissionsOnJoin: []
settings:
sessions:
  enabled: false
  timeout: 10
  sessionExpireOnIpChange: false
restrictions:
  allowChat: false
  allowCommands:
  - /login
  - /register
  - /l
  - /reg
  - /passpartu
  - /email
  - /captcha
  - /gir
  - /kayit
  maxRegPerIp: 3
  maxNicknameLength: 20
  ForceSingleSession: true
  ForceSpawnLocOnJoinEnabled: true
  SaveQuitLocation: false
  AllowRestrictedUser: false
  AllowedRestrictedUser:
  - playername;127.0.0.1
  kickNonRegistered: false
  kickOnWrongPassword: true
  teleportUnAuthedToSpawn: false
  minNicknameLength: 3
  allowMovement: false
  timeout: 30
  allowedNicknameCharacters: '[a-zA-Z0-9_]*'
  allowedMovementRadius: 100
  enablePasswordVerifier: true
  ProtectInventoryBeforeLogIn: true
  displayOtherAccounts: false
  ForceSpawnOnTheseWorlds:
  - world
  - world_nether
  - world_the_end
  banUnsafedIP: false
  spawnPriority: authme,essentials,multiverse,default
  maxLoginPerIp: 0
  maxJoinPerIp: 0
  noTeleport: false
  allowedPasswordCharacters: '[a-zA-Z0-9_?!@+&-]*'
GameMode:
  ForceSurvivalMode: false
  ResetInventoryIfCreative: false
  ForceOnlyAfterLogin: false
security:
  minPasswordLength: 4
  unLoggedinGroup: unLoggedinGroup
  passwordHash: md5
  doubleMD5SaltLength: 8
  supportOldPasswordHash: false
  unsafePasswords: []
registration:
  enabled: true
  messageInterval: 5
  force: true
  enableEmailRegistrationSystem: false
  doubleEmailCheck: false
  forceKickAfterRegister: false
  forceLoginAfterRegister: false
unrestrictions:
  UnrestrictedName: []
messagesLanguage: en
forceCommands: []
forceCommandsAsConsole: []
forceRegisterCommands: []
forceRegisterCommandsAsConsole: []
useWelcomeMessage: true
broadcastWelcomeMessage: false
delayJoinMessage: false
applyBlindEffect: false
ExternalBoardOptions:
mySQLColumnSalt: ''
mySQLColumnGroup: ''
nonActivedUserGroup: -1
mySQLOtherUsernameColumns: []
bCryptLog2Round: 10
phpbbTablePrefix: phpbb_
phpbbActivatedGroupId: 2
wordpressTablePrefix: wp_
permission:
EnablePermissionCheck: false
BackupSystem:
ActivateBackup: false
OnServerStart: false
OnServerStop: true
MysqlWindowsPath: C:\\Program Files\\MySQL\\MySQL Server 5.1\\
Passpartu:
enablePasspartu: false
Security:
SQLProblem:
  stopServer: true
ReloadCommand:
  useReloadCommandSupport: true
console:
  noConsoleSpam: false
  removePassword: true
captcha:
  useCaptcha: false
  maxLoginTry: 5
  captchaLength: 5
Converter:
Rakamak:
  fileName: users.rak
  useIP: false
  ipFileName: UsersIp.rak
CrazyLogin:
  fileName: accounts.db
Email:
mailSMTP: smtp.gmail.com
mailPort: 465
mailAccount: ''
mailPassword: ''
mailSenderName: ''
RecoveryPasswordLength: 8
mailSubject: Your new AuthMe Password
mailText: 'Dear <playername>, <br /><br /> This is your new AuthMe password for
  the server <br /><br /> <servername> : <br /><br /> <generatedpass><br /><br />Do
  not forget to change password after login! <br /> /changepassword <generatedpass>
  newPassword'
maxRegPerEmail: 1
recallPlayers: false
delayRecall: 5
emailBlacklisted:
- 10minutemail.com
emailWhitelisted: []
Hooks:
multiverse: true
chestshop: true
bungeecord: false
notifications: true
disableSocialSpy: true
useEssentialsMotd: false
customAttributes: false
Performances: {}
Purge:
useAutoPurge: false
daysBeforeRemovePlayer: 60
removePlayerDat: false
removeEssentialsFile: false
defaultWorld: world
removeLimitedCreativesInventories: false
removeAntiXRayFile: false
removePermissions: false
Protection:
enableProtection: true
countries:
- TR
- AZ
- CY
countriesBlacklist:
- A1
- US
- GB
enableAntiBot: true
antiBotSensibility: 5
antiBotDuration: 10
VeryGames:
enableIpCheck: false</textarea>
  <hr style="margin:0">
  <div class="block-content" style="padding-bottom:0;">
    <div class="alert alert-info">Yukarıdaki kodu kopyalayıp <b>authme</b> klasöründeki <code>config.yml</code> dosyasının içine atınız.</div>
  </div>
</div>

<script type="text/javascript">
  var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
    lineNumbers: true,
    mode: 'text/x-yaml'
  });
</script>