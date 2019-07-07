DROP DATABASE IF EXISTS AxisPrisonersDB;
CREATE DATABASE AxisPrisonersDB;
USE AxisPrisonersDB;

CREATE TABLE User (
    username VARCHAR(64) NOT NULL,
    password VARCHAR(32) NOT NULL,

    -- Fields the user can mark as hidden
    firstName VARCHAR(64) NOT NULL,
    lastName VARCHAR(64),
    email VARCHAR(64) NOT NULL,
    dateOfBirth DATE NOT NULL,
    gender VARCHAR(8) NOT NULL,

    -- Mark if the user has hidden them
    nameHidden TINYINT NOT NULL DEFAULT -1,
    emailHidden TINYINT NOT NULL DEFAULT -1,
    dateOfBirthHidden TINYINT NOT NULL DEFAULT -1,
    genderHidden TINYINT NOT NULL DEFAULT -1,

    `rank` INTEGER NOT NULL DEFAULT 2,
    bio VARCHAR(1024),
    imageUrl VARCHAR(5000) NOT NULL DEFAULT 'http://ec2-52-87-203-249.compute-1.amazonaws.com/PrisonersOfWarTwo/public/img/profile-img.jpg',
    CHECK (gender IN ('Male', 'Female', 'Other')),
    CHECK (`rank` IN (1, 2, 3, 4)),
    CHECK (
        (nameHidden = -1 OR nameHidden = 1)
        AND (emailHidden = -1 OR emailHidden = 1)
        AND (dateOfBirthHidden = -1 OR dateOfBirthHidden = 1)
        AND (genderHidden = -1 OR genderHidden = 1)
    ),
    PRIMARY KEY (username)
);

INSERT INTO User (username, password, firstName, lastName, email, dateOfBirth, gender, `rank`)
    VALUES
    ('admin', '202cb962ac59075b964b07152d234b70', 'Lorenzo', 'Raras', 'lwraras@vt.edu', '1997-07-27', 'Male', 4),
    ('annreaf1', '13dfafa881799a7a8093fcee062ab0b4', 'Annrea', 'Fowler', 'annreaf1@vt.edu', '1997-04-02', 'Female', 3);

CREATE TABLE Activity (
  id INTEGER NOT NULL AUTO_INCREMENT,
  type VARCHAR(64) NOT NULL,
  description VARCHAR(512),
  PRIMARY KEY (id)
);

/*
NOTE: The default imageUrl values in Camp and Prisoner must be changed
before we migrate to the EC2 instance.
*/

CREATE TABLE Camp (
  id INTEGER NOT NULL AUTO_INCREMENT,
  name VARCHAR(5000) NOT NULL,
  purpose VARCHAR(5000),
  demographic VARCHAR(5000),
  location VARCHAR(5000) NOT NULL,
  latitude FLOAT,
  longitude FLOAT,
  numberOfPrisoners INTEGER,
  dateBegan DATE,
  dateEnded DATE,
  warden VARCHAR(5000),
  imageUrl VARCHAR(5000) NOT NULL DEFAULT 'http://ec2-52-87-203-249.compute-1.amazonaws.com/PrisonersOfWarTwo/public/img/default-camp.jpg',
  creator VARCHAR(64) NOT NULL,
  FOREIGN KEY (creator) REFERENCES User(username)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  PRIMARY KEY (id)
);

/*For dates with unknown months and days, it is defaulted to 01*/
INSERT INTO Camp (creator, name, latitude, longitude, purpose, demographic, location, dateBegan, dateEnded, imageUrl) VALUES
	('admin', 'Camp Aliceville', 33.1296, -88.1514, 'Provided a rich cultural life for prisoners, which included musical groups, theatre productions, and a camp newspaper.', '4500 German Prisoners', 'Aliceville, Alabama', '1943-07-01', '1945-09-01', 'http://www.alicevillemuseum.org/uploads/9/4/3/7/94376225/edited/gp-1.jpeg?1485801916'),
	('admin', 'Camp Allen', 36.8508, -76.2859, NULL, 'Unknown', 'Norfolk, Virginia', NULL, NULL, 'http://ec2-52-87-203-249.compute-1.amazonaws.com/PrisonersOfWarTwo/public/img/default-camp.jpg'),
	('admin', 'Fort Andrews', 41.6815, -70.9196, 'The fort served as a prisoner-of-war camp for Italian prisoners during World War II, who were employed as laborers following the Italian surrender to the Allies in 1943.', 'Italian Soldiers', 'Coast Defenses of Boston, Massachusetts', '1903-01-01', '1947-01-01', 'http://www.bostonharborbeacon.com/wp-content/uploads/2013/03/photo-523.jpg'),
	('admin', 'Camp Angel Island', 37.8609, -122.433, NULL, 'Japanese, German, and Italian Soldiers', 'Angel Island, California', NULL, NULL, 'https://evan.marathonswimmers.org/wp-content/uploads/2015/07/Angel-Island-Aerial-e1436575112165.jpg'),
	('admin', 'Camp Ashby', 36.7515, -76.053, 'At the time of erection, the property which Camp Ashby resided had the Tidewater Victory Memorial Hospital as its dominant feature.', '6000 German Soldiers', 'Princess Anne County, Virginia', '1942-01-01', NULL, 'http://www.nnhs65.com/NMC/Site-of-German-WWII-POW-Cam.jpg'),
	('admin', 'Camp Atlanta', 40.3688, -99.4735, 'The location of Camp Atlanta was chosen due to it being well into the interior of the United States.', '3000 German Soldiers', 'Atlanta, Nebraska', '1943-09-01', NULL, 'http://msnbcmedia.msn.com/i/MSNBC/Components/Photo/_new/pb-101207-eg-01.jpg'),
	('admin', 'Camp Atterbury', 40.4593, -85.4965, 'Served as a military and civilian training base under the auspices of the Indiana National Guard.', '15000 German and Italian Soldiers', 'Atterbury, Indiana', '1942-01-01', '1946-01-01', 'https://indianapublicmedia.org/news/files/2012/07/Final-image.jpg'),
	('admin', 'Camp Barkeley', 32.4487, -99.7331, 'Camp Barkeley was originally a training camp for the 45th Infantry Division, 11th Armored Division, and 12th Armorerd Division but was repurposed to hold German prisoners during 1944.', '840 German Soldiers', 'Abilene, Texas', '1944-02-01', '1945-09-01', 'http://www.11tharmoreddivision.com/guestbook/public/img-1248921884.jpg'),
	('admin', 'Camp Beale', 39.1457, -121.591, 'German prisoners of war were held captive on the base during World War II; a block of barred prison cells still stands at the base, and the drawings of the POWs remain vivid on the walls of the prison cells', 'German Soldiers', 'Marysville, California', '1942-01-01', NULL, 'http://www.usmilitariaforum.com/uploads/monthly_08_2008/post-837-1218684862.jpg'),
	('admin', 'Camp Beaver', 33.77, -82.8545, 'The camp held 300 German prisoners of war in a tent city encampment where the Wayland Academy field house now stands.', '300 German Soldiers', 'Beaver Dam, Washington', NULL, NULL, 'https://www.telegraph.co.uk/content/dam/photography/2017/06/07/documenta-05_1-xlarge_trans_NvBQzQNjv4BqRno0wBcbj8R7Bar7Q2cKrBKi2sT3vi7ux2-RDZwC4QA.jpg');

CREATE TABLE Prisoner (
  id INTEGER NOT NULL AUTO_INCREMENT,
  name VARCHAR(64) NOT NULL,
  dateOfBirth DATE,
  dateOfDeath DATE,
  `rank` VARCHAR(64),
  countryOfOrigin VARCHAR(64),
  creator VARCHAR(64) NOT NULL,
  imageUrl VARCHAR(128) NOT NULL DEFAULT 'http://ec2-52-87-203-249.compute-1.amazonaws.com/PrisonersOfWarTwo/public/img/profile-img.jpg',
  PRIMARY KEY (id)
);

CREATE TABLE Event (
  id INTEGER NOT NULL AUTO_INCREMENT,
  prisonerId INTEGER NOT NULL,
  title VARCHAR(64) NOT NULL,
  dateHappened DATE NOT NULL,
  description VARCHAR(512),
  FOREIGN KEY (prisonerId) REFERENCES Prisoner(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  PRIMARY KEY (id)
);

CREATE TABLE Comment (
  id INTEGER NOT NULL AUTO_INCREMENT,
  username VARCHAR(64) NOT NULL,
  campId INTEGER,
  prisonerId INTEGER,
  profileId VARCHAR(64),
  content VARCHAR(512) NOT NULL,
  madeOn TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (username) REFERENCES User(username)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (campId) REFERENCES Camp(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (prisonerId) REFERENCES Prisoner(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (profileId) REFERENCES User(username)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CHECK (username <> profileId),
  PRIMARY KEY (id)
);

CREATE TABLE Activity_Feed (
  id INTEGER NOT NULL AUTO_INCREMENT,
  username VARCHAR(64) NOT NULL,

  /*
  The following ids references one of the tables above that the user has the
  ability to change.  The type will tell the developer which table to join this
  id on; however, this does mean that the developer is responsible for enforcing
  referential integrity.
  */
  entityId INTEGER NOT NULL,
  otherUser VARCHAR(64) NOT NULL,
  entityName VARCHAR(5000) NOT NULL, -- If the feed type is a delete action
  type VARCHAR(64) NOT NULL,

  happened TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (username) REFERENCES User(username)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  PRIMARY KEY (id)
);

CREATE TABLE Report (
  id INTEGER NOT NULL AUTO_INCREMENT,
  reporter VARCHAR(64) NOT NULL,
  reportee VARCHAR(64) NOT NULL,
  description VARCHAR(512) NOT NULL,
  FOREIGN KEY (reporter) REFERENCES User(username)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (reportee) REFERENCES User(username)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CHECK (reporter <> reportee),
  PRIMARY KEY (id)
);

CREATE TABLE Feedback (
  campId INTEGER NOT NULL,
  content VARCHAR(5000) NOT NULL,
  FOREIGN KEY (campId) REFERENCES Camp(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  PRIMARY KEY (campId)
);

/*
The following two tables are mapping tables, that represent the many-many
relationships that they have foreign keys for.  In database management, a
mapping table uses a composite primary key of both foreign keys so that a single
field can be duplicated.
*/

CREATE TABLE Housing (
  prisonerId INTEGER NOT NULL,
  campId INTEGER NOT NULL,
  FOREIGN KEY (prisonerId) REFERENCES Prisoner(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (campId) REFERENCES Camp(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  PRIMARY KEY (prisonerId, campId)
);

CREATE TABLE Workload (
  activityId INTEGER NOT NULL,
  campId INTEGER NOT NULL,
  FOREIGN KEY (activityId) REFERENCES Activity(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (campId) REFERENCES Camp(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  PRIMARY KEY (activityId, campId)
);

CREATE TABLE Follows (
  followee VARCHAR(64) NOT NULL,
  follower VARCHAR(64) NOT NULL,
  FOREIGN KEY (followee) REFERENCES User(username)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (follower) REFERENCES User(username)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CHECK (followee <> follower),
  PRIMARY KEY (followee, follower)
);
