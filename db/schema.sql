
CREATE SCHEMA IF NOT EXISTS `employee_management` DEFAULT CHARACTER SET utf8 ;
USE `employee_management` ;

-- -----------------------------------------------------
-- Table `employee_management`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `employee_management`.`user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(2048) NOT NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `user_type` INT NOT NULL,
  `image_url` VARCHAR(2048),
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE);


-- -----------------------------------------------------
-- Table `employee_management`.`applications`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `employee_management`.`applications` (
  `application_id` INT NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(45) NOT NULL,
  `submission_date` DATETIME NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `requested_days` INT NOT NULL,
  `user_id` INT NOT NULL,
  `reason` VARCHAR(255) DEFAULT '',
  PRIMARY KEY (`application_id`),
  UNIQUE INDEX `application_id_UNIQUE` (`application_id` ASC) VISIBLE,
  INDEX `fk_applications_user_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_applications_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `employee_management`.`user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

