<?php




try {
    $connx = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_DATA, DB_USER, DB_PASSWORD);
    $connx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_license`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_license = "CREATE TABLE `u_license` (
  `id` int NOT NULL,
  `udid` varchar(32) NOT NULL,
  `license` varchar(512) NOT NULL,
  `product` text NOT NULL,
  `boundProduct` int NOT NULL DEFAULT '1',
  `expire` bigint NOT NULL,
  `maxIps` int NOT NULL DEFAULT '3',
  `ips` text,
  `time` text,
  `status` varchar(12) NOT NULL DEFAULT '1',
  `use` int NOT NULL DEFAULT '1',
  `resetips` varchar(12) NOT NULL DEFAULT '5',
  `by` text NOT NULL,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_license);
    $connx->exec("ALTER TABLE `u_license` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

}
try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_note`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_license = "CREATE TABLE `u_note` (
  `id` int NOT NULL,
  `uid` int NOT NULL,
  `lid` int NOT NULL,
  `type` varchar(32) NOT NULL,
  `title` varchar(256) NOT NULL,
  `descriptions` text NOT NULL,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_license);
    $connx->exec("ALTER TABLE `u_note` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

}
try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_product`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_license = "CREATE TABLE `u_product` (
  `id` int NOT NULL,
  `name` text NOT NULL,
  `direction` text NOT NULL,
  `priority` varchar(12) NOT NULL,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_license);
    $connx->exec("ALTER TABLE `u_product` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

}
try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_server`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_license = "CREATE TABLE `u_server` (
  `id` int NOT NULL,
  `license` text NOT NULL,
  `ip` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'process',
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_license);
    $connx->exec("ALTER TABLE `u_server` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

}
try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_user`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_license = "CREATE TABLE `u_user` (
  `id` int NOT NULL,
  `udid` varchar(32) NOT NULL,
  `name` text,
  `avatar` text,
  `rank` varchar(12) NOT NULL DEFAULT 'user',
  `theme` varchar(8) NOT NULL DEFAULT 'false',
  `ips` text,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_license);
    $connx->exec("ALTER TABLE `u_user` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

}
try {
    $sqlLicense = $connx->prepare("DESCRIBE `u_user_permissions`");
    $sqlLicense->execute();

} catch (PDOException $e) {
    $u_license = "CREATE TABLE `u_user_permissions` (
  `id` int NOT NULL,
  `udid` text NOT NULL,
  `permission` text NOT NULL,
  `since` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8";

    $connx->exec($u_license);
    $connx->exec("ALTER TABLE `u_user_permissions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

}

?>