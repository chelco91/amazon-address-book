<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title>Amazon Address Book</title>
		<link rel="stylesheet" href="css/styles.css" />
		<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Tangerine">
	</head>
	<body>
		<div class="container">
			<header>
				<h1>Amazon Address Book</h1>
			</header>
			<main>
				<div class="persona">
					<div class="top">
						<div class="width33">
							<img src="images/trevor-rabin.jpg" height="100px" width="160px"/>
							<p><strong>Name: </strong>Trevor Rabin</p>
							<p><strong>Age: </strong>35</p>
						</div>
						<div class="width33">
							<p><strong>Profession: </strong>
								Trevor is a hard working book salesman. He likes books. He saw Amazon had just the sale for him.
							</p>
						</div>
						<div class="width33">
							<p><strong>Technology: </strong>
								Trevor utilizes lots of web applications for his business. Also he is VERY familiar with Amazon's site.
							</p>
						</div>
					</div>
					<hr />
					<div class="bottom">
						<div class="width33">
							<p><strong>Attitudes and Behaviors: </strong>
								Trevor loves to make reading suggestions to all his family and friends. He will typically send out a few books every week to them.
							</p>
						</div>
						<div class="width33">
							<p><strong>Frustrations and Needs: </strong>
								Trevor has lots of trouble remembering which friend lives where. He needs some place to store lots of different shipping addresses. Also his shipping and billing address don't match very often.
							</p>
						</div>
						<div class="width33">
							<p><strong>Goals: </strong>
								Trevor's goal right now is to purchase his book in a timely manner allowing him to catch the discounted price.
							</p>
						</div>
					</div>
				</div>
				<div class="useCases">
					<h2>Use Cases</h2>
					<ol>
						<li>User types in amazon.com into their browser.</li>
						<li>User logs in using their email and password.</li>
						<li>User selects products to purchase and sends them to their cart.</li>
						<li>When the user is done shopping, they click on their shopping cart.</li>
						<li>User then clicks "Check Out" to finalize their purchases.</li>
						<li>User is prompted for a shipping address to send products to.</li>
						<li>System populates order with a default shipping and billing address.</li>
						<li>User chooses to use default values.</li>
					</ol>
				</div>
				<div class="erd">
					<h2>Entity Relational Diagram</h2>
					<img src="images/amazon-address-book.jpg" alt="Amazon Address Book ERD">
				</div>
				<div class="ddl">
					<h2>Data Description Language</h2>
					<pre>
DROP TABLE IF EXISTS orderAddress;
DROP TABLE IF EXISTS purchaseOrder;
DROP TABLE IF EXISTS address;
DROP TABLE IF EXISTS profile;

CREATE TABLE profile (
	profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	password VARCHAR(32) NOT NULL,
	email VARCHAR(128) NOT NULL,
	UNIQUE(email),
	PRIMARY KEY(profileId)
);

CREATE TABLE address (
	addressId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	streetNumber VARCHAR(65) NOT NULL,
	city VARCHAR(32) NOT NULL,
	state VARCHAR(2) NOT NULL,
	zip SMALLINT NOT NULL,
	phoneNumber VARCHAR(32) NOT NULL,
	INDEX(profileId),
	PRIMARY KEY(addressId),
	FOREIGN KEY(profileId) REFERENCES profile(profileId)
);

CREATE TABLE purchaseOrder (
	orderId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	totalItems SMALLINT UNSIGNED NOT NULL,
	totalPrice DOUBLE NOT NULL,
	shippingCost DOUBLE NOT NULL,
	deliveryDate DATE NOT NULL,
	INDEX(profileId),
	PRIMARY KEY(orderId),
	FOREIGN KEY(profileId) REFERENCES profile(profileId)
);

CREATE TABLE orderAddress (
	addressId INT UNSIGNED NOT NULL,
	orderId INT UNSIGNED NOT NULL,
	formOfPayment VARCHAR(40) NOT NULL,
	INDEX(addressId),
	INDEX(orderId),
	FOREIGN KEY(addressId) REFERENCES address(addressId),
	FOREIGN KEY(orderId) REFERENCES purchaseOrder(orderId)
); 
					</pre>
				</div>
			</main>
		</div>
	</body>
</html>