Prijavi se - ako je kao admin -> mogu brisati, dodavati nove artikle, kada se doda novi artikl, automatski se to dodaje u bazu, ime artikla, cijena, količina na stanju.
		   - ako kao običan guest -> normalan layout stranice

Spajanje na bazu - kada se doda artikl u košaricu, automatski se iz baze oduzima za tu količinu, a ako korisnik ugasi stranicu tj izađe a ne naruči artikl, vraća se količina na zalihu i naravno kada netko naruči da se makne sa zalihe tj iz baze da se oduzme jednak broj artikla

Ako nekog artikla više nema na stanju, mora pisati "Trenutno nema na stanju"

Ako se nisam prijavio kada stisnem dodaj u košaricu prvo iskače log in screen




Sad mi treba baza, jedna sa računima(da li je normalan korisinik ili admin) i jedna sa svim artiklima kojem imam(cijena, kolicina, naziv). Ukoliko sam normalan korisnik onda samo izbaci poruku da sam uspješno ulogiran i mogu dodavati stvari u košaricu, te ako se ne prijavim nikako kada pritisnem "dodaj u kosaricu" onda se moram prijaviti kako bi mogao dodavati stvari u kosaricu. Ako sam kao admin prijavljen onda mogu dodavati nove artikle na stranicu. Kada dodam novi artikl treba mi se dodati artikl u  npr. komponente.html, trebam imati mogućnost prikaza tog artikla kada pritisnem detalji da mi otvori posebnu stranicu tog artikla kako bi ga mogao dodati u košaricu te mi se taj artikl mora dodati u bazi sa parametrima koje sam unio kao admin kada sam dodavao artikl na stranicu(kolicina, cijena, naziv). 



17.5.
Sav inline css prebaciti u style.css