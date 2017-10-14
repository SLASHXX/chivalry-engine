CREATE TABLE `jobs` (
  `jRANK` INT(11) UNSIGNED NULL DEFAULT NULL AUTO_INCREMENT ,
  `jNAME` TEXT NOT NULL ,
  `jSTART` INT(11) UNSIGNED NOT NULL ,
  `jDESC` TEXT NOT NULL ,
  `jBOSS` INT NOT NULL ,
  UNIQUE (`jRANK`)
) ENGINE = MyISAM;

CREATE TABLE `job_ranks` (
  `jrID` INT(11) UNSIGNED NULL DEFAULT NULL AUTO_INCREMENT ,
  `jrJOB` INT(11) UNSIGNED NOT NULL ,
  `jrPRIMPAY` INT(11) UNSIGNED NOT NULL ,
  `jrSECONDARY` INT(11) UNSIGNED NOT NULL ,
  `jrACT` INT(11) UNSIGNED NOT NULL ,
  `jrSTR` INT(11) UNSIGNED NOT NULL ,
  `jrLAB` INT(11) UNSIGNED NOT NULL ,
  `jrIQ` INT(11) UNSIGNED NOT NULL ,
  UNIQUE (`jrID`)
) ENGINE = MyISAM;

ALTER TABLE `job_ranks` ADD `jrRANK` TEXT NOT NULL AFTER `jrID`;
ALTER TABLE `jobs` CHANGE `jBOSS` `jBOSS` TEXT NOT NULL;

ALTER TABLE `users` ADD `job` INT(11) UNSIGNED NOT NULL AFTER `secondary_currency`,
  ADD `jobrank` INT(11) UNSIGNED NOT NULL AFTER `job`;

  ALTER TABLE `users` ADD `jobwork` INT(11) UNSIGNED NOT NULL AFTER `jobrank`;

CREATE TABLE `guild_armory` (
  `gaID` INT(11) UNSIGNED NULL AUTO_INCREMENT ,
  `gaGUILD` INT(11) UNSIGNED NOT NULL ,
  `gaITEM` INT(11) UNSIGNED NOT NULL ,
  `gaQTY` INT(11) UNSIGNED NOT NULL ,
   UNIQUE (`gaID`)
) ENGINE = MyISAM;

CREATE TABLE `guild_crimes` (
  `gcID` INT(11) UNSIGNED NULL DEFAULT NULL AUTO_INCREMENT ,
  `gcNAME` INT(11) UNSIGNED NOT NULL ,
  `gcUSERS` INT(11) UNSIGNED NOT NULL ,
  `gcSTART` TEXT NOT NULL ,
  `gcSUCC` TEXT NOT NULL ,
  `gcFAIL` TEXT NOT NULL ,
  `gcMINCASH` INT(11) UNSIGNED NOT NULL ,
  `gcMAXCASH` INT(11) UNSIGNED NOT NULL ,
  PRIMARY KEY (`gcID`)
) ENGINE = MyISAM;

ALTER TABLE `guild_crimes` CHANGE `gcNAME` `gcNAME` TEXT NOT NULL;

ALTER TABLE `guild` ADD `guild_crime` INT(11) UNSIGNED NOT NULL AFTER `guild_announcement`, ADD `guild_crime_done` INT(11) UNSIGNED NOT NULL AFTER `guild_crime`;

CREATE TABLE `guild_crime_log` (
  `gclID` INT(11) UNSIGNED NULL DEFAULT NULL AUTO_INCREMENT ,
  `gclCID` INT(11) UNSIGNED NOT NULL ,
  `gclGUILD` INT(11) UNSIGNED NOT NULL ,
  `gclLOG` TEXT NOT NULL ,
  `gclRESULT` TEXT NOT NULL ,
  `gclWINNING` INT(11) UNSIGNED NOT NULL ,
  `gclTIME` INT(11) UNSIGNED NOT NULL ,
   PRIMARY KEY (`gclID`)
) ENGINE = MyISAM;