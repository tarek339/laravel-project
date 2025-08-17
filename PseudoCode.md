## **Zuweisung von Entitäten**

Dieses Dokument beschreibt die Benutzeroberfläche und die zugrunde liegende Funktionalität zur Zuweisung von Entitäten (Fahrer, LKW, Trailer) in der Anwendung. Die Zuweisungslogik stellt sicher, dass die Beziehungen zwischen den Entitäten konsistent und nachvollziehbar sind.

---

### **Zuweisungslogik und Optionen**

Die Zuweisungsfunktionalität ermöglicht die Verknüpfung folgender Entitäten:

- **Fahrer ↔ LKW:** Ein Fahrer kann einem LKW zugewiesen werden und umgekehrt.
- **LKW ↔ Trailer:** Ein LKW kann einem Trailer zugewiesen werden und umgekehrt.

---

### **Benutzeroberfläche (UI) für die Zuweisung**

Die Zuweisung erfolgt über Dropdown-Menüs in den jeweiligen Übersichten. Die Standardeinstellung für alle Zuweisungen ist "keine Zuweisung", dargestellt durch einen Bindestrich (`-`).

#### **Zuweisung aus der Fahrer-Übersicht**

In der Tabellenansicht der Fahrer kann jedem Fahrer ein LKW zugewiesen werden.

- **Dropdown-Inhalt:** Das Dropdown-Menü listet alle verfügbaren LKW des Unternehmens auf.
- **Aktion:** Der ausgewählte LKW wird dem Fahrer zugewiesen.

#### **Zuweisung aus der LKW-Übersicht**

In der Tabellenansicht der LKW können diese sowohl einem Fahrer als auch einem Trailer zugewiesen werden.

- **Dropdown-Inhalt:** Das Dropdown-Menü enthält alle verfügbaren Fahrer und Trailer des Unternehmens.
- **Aktion:** Der LKW wird entweder dem ausgewählten Fahrer oder Trailer zugewiesen.

#### **Zuweisung aus der Trailer-Übersicht**

In der Tabellenansicht der Trailer kann jedem Trailer ein LKW zugewiesen werden.

- **Dropdown-Inhalt:** Das Dropdown-Menü zeigt alle verfügbaren LKW des Unternehmens an.
- **Aktion:** Der ausgewählte LKW wird dem Trailer zugewiesen.

---

### **Technische Implementierung der Zuweisung**

Die Zuweisungslogik wird durch einen `onChange`-Handler ausgelöst.

1.  **Client-Seite (UI):** Bei Auswahl einer Entität im Dropdown-Menü wird ein `onChange`-Ereignis ausgelöst.
2.  **HTTP-Anfrage:** Der `onChange`-Handler sendet eine HTTP-Anfrage an den Controller im Backend. Der **Body der Anfrage** enthält die eindeutigen IDs (`unique ID`) beider zu verknüpfender Entitäten (z. B. `Fahrer-ID` und `LKW-ID`).
3.  **Server-Seite (Backend):**
    - Der Controller sucht die entsprechenden Entitäten in der Datenbank.
    - **Datenbank-Update:**
        - Beim Fahrer wird das Kennzeichen des LKW in der Eigenschaft `assigned_to` gespeichert.
        - Beim LKW werden Vor- und Nachname des Fahrers in der Eigenschaft `assigned_to` gespeichert.
4.  **Rückmeldung an den Client:** Nach erfolgreicher Speicherung sendet der Controller eine Bestätigung zurück an den Client mit der Meldung: **"Zuweisung erfolgreich durchgeführt."**
