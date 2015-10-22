CREATE TABLE IF NOT EXISTS "system" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "version" varchar,
  "domain" varchar,
  "ip" varchar,
  "os" varchar,
  "server" varchar,
  "php" varchar,
  "db" varchar,
  "installed" datetime,
  UNIQUE ("id")
);

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('system', 0);

CREATE TABLE IF NOT EXISTS "modules" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" varchar,
  "version" varchar,
  "domain" varchar,
  "ip" varchar,
  "os" varchar,
  "server" varchar,
  "php" varchar,
  "db" varchar,
  "installed" datetime,
  UNIQUE ("id")
);

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('modules', 0);

CREATE TABLE IF NOT EXISTS "themes" (
  "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
  "name" varchar,
  "version" varchar,
  "domain" varchar,
  "ip" varchar,
  "os" varchar,
  "server" varchar,
  "php" varchar,
  "db" varchar,
  "installed" datetime,
  UNIQUE ("id")
);

INSERT INTO "sqlite_sequence" ("name", "seq") VALUES ('themes', 0);
