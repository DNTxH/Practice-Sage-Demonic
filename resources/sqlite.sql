-- #! sqlite
-- #{ core

-- #  { init
CREATE TABLE IF NOT EXISTS vaults(
	username VARCHAR(25) NOT NULL,
	number TINYINT UNSIGNED NOT NULL,
	data BLOB NOT NULL,
	PRIMARY KEY(username, number)
);
-- #  }

-- #  { load
-- #    :username string
-- #    :number int
SELECT HEX(data) AS data FROM vaults WHERE username=:username AND number=:number;
-- #  }

-- #  { save
-- #    :username string
-- #    :number int
-- #    :data string
INSERT OR REPLACE INTO vaults(username, number, data) VALUES(:username, :number, :data);
-- #  }

-- #}