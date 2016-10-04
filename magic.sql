CREATE TABLE Deck (
		deck_ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(30),
		creator VARCHAR(30)
		);
			
CREATE TABLE Card (
		card_ID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(30),
		color VARCHAR(30),
		castingCost VARCHAR(30),
		type VARCHAR(30)
		);
			
	
CREATE TABLE Decklist (
		card_ID INT(6),
		deck_ID INT(6),
		card_name VARCHAR(30),
		deck_name VARCHAR(30),
		copies INT(1),
		PRIMARY KEY(card_ID,deck_ID),
		CONSTRAINT Const_Decklist_Card_fk
			FOREIGN KEY Card_fk (card_ID) REFERENCES Card (card_ID)
			ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT Const_Decklist_Deck_fk
			FOREIGN KEY Deck_fk (deck_ID) REFERENCES Deck (deck_ID)
			ON DELETE CASCADE ON UPDATE CASCADE
);