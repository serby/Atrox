SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;
SET search_path = public, pg_catalog;
SET default_tablespace = '';
SET default_with_oids = false;


CREATE DATABASE "AtroxTest" WITH ENCODING='UTF8' CONNECTION LIMIT=-1;
ALTER DATABASE "AtroxTest" SET TimeZone=0;
ALTER DATABASE "AtroxTest" OWNER TO postgres;

\connect "AtroxTest"

CREATE TABLE "Blog" (
    "Id" serial NOT NULL,
    "Title" text NOT NULL,
    "Description" text NOT NULL,
    "DateCreated" timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public."Blog" OWNER TO postgres;

INSERT INTO "Blog" ("Id", "Title", "Description", "DateCreated") VALUES (1, 'First Blog Entry', 'I love blogging', '2008-05-22 17:15:33.460379');
INSERT INTO "Blog" ("Id", "Title", "Description", "DateCreated") VALUES (2, 'I am a bog', 'A test blog for the featured member section.', '2008-05-28 17:51:24.943435');
INSERT INTO "Blog" ("Id", "Title", "Description", "DateCreated") VALUES (3, 'A new blog entry', 'This is a blog description', '2008-06-20 12:44:56.752169');
INSERT INTO "Blog" ("Id", "Title", "Description", "DateCreated") VALUES (4, 'Aenean elit ligula, rutrum eu,', 'Small Blog Post', '2008-10-28 15:50:24.459776');

ALTER TABLE ONLY "Blog"
    ADD CONSTRAINT "Blog_pkey" PRIMARY KEY ("Id");

CREATE INDEX "Blog_Title_Idx" ON "Blog" USING btree ("Title");
SELECT setval('public."Blog_Id_seq"', 4, true);

REVOKE ALL ON TABLE "Blog" FROM PUBLIC;
REVOKE ALL ON TABLE "Blog" FROM postgres;
GRANT ALL ON TABLE "Blog" TO postgres;
GRANT ALL ON TABLE "Blog" TO "WebUserGroup" WITH GRANT OPTION;
GRANT ALL ON TABLE "Blog_Id_seq" TO "WebUserGroup" WITH GRANT OPTION;	