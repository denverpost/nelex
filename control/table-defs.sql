CREATE TABLE IF NOT EXISTS election (
 election_id integer PRIMARY KEY, /* an election is a single day's election */
 election_type text NOT NULL, /* Primary/General/etc */
 election_date integer NOT NULL, /* Date of actual vote in format YYYYMMDD */
 election_live integer NOT NULL /* Whether to check associated _vote_s for new results (BOOLEAN) */
);
CREATE TABLE IF NOT EXISTS vote (
 vote_id integer PRIMARY KEY, /* a vote is a set of data associated with an _election_ and a _place_ */
 vote_name text NOT NULL, /* A display name visible to users */
 vote_url text NOT NULL, /* A Clarity elections URL to source data from */
 vote_place integer NOT NULL, /* a place.place_id associated with this data */
 vote_election integer NOT NULL /* an election.election_id associated with this data */
);
CREATE TABLE IF NOT EXISTS places (
 place_id integer PRIMARY KEY, /* a place is a geographical thing like a state or county. Most will be counties */
 place_name text NOT NULL, /* A display name visible to users */
 place_parent integer NOT NULL /* counties have parent states; in our case nearly always CO */
);