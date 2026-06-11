# Über dieses Projekt

Dieser Demo-Stack zeigt, wie ein Support-Ticket-System mit einer KI-gestützten Priorisierung verknüpft werden kann:

1. Über ein Webformular (Symfony-Webapp) wird ein Support-Ticket erstellt.
2. Das Ticket wird per Webhook an einen n8n-Workflow übergeben.
3. Der Workflow lässt die Priorität des Tickets per AI klassifizieren.
4. Je nach ermittelter Priorität verschickt der Workflow eine Push-Benachrichtigung über Gotify.

# Schnellstart zum Ausprobieren

So bringst du den kompletten Demo-Stack zum Laufen und siehst eine Push-Benachrichtigung in Gotify, sobald ein Support-Ticket eingereicht wird.

### 1. Repository klonen

**Linux / macOS (bash):**
```bash
git clone https://github.com/aleyaley789/n8n-demo.git
cd n8n-demo
```

**Windows (PowerShell):**
```powershell
git clone https://github.com/aleyaley789/n8n-demo.git
cd n8n-demo
```

### 2. Docker Stack starten

```bash
docker compose up -d
```

Beim ersten Start werden Images gebaut und die Webapp-Abhängigkeiten installiert - das kann ein paar Minuten dauern. Mit `docker compose ps` prüfen, ob alle Container (`n8n`, `gotify`, `webapp_php`, `webapp_nginx`, `webapp_mysql`) laufen bzw. healthy sind.

### 3. Workflow "Support Ticket Demo" in n8n importieren

n8n unter [http://localhost:5678](http://localhost:5678) öffnen und beim ersten Aufruf einen Owner-Account anlegen.

Anschließend den mitgelieferten Workflow importieren:

**Linux / macOS (bash):**
```bash
docker cp "./workflows/support-ticket-ai-demo.json" "n8n:/home/node/.n8n/import/"
docker exec n8n n8n import:workflow --input=/home/node/.n8n/import/support-ticket-ai-demo.json
```

**Windows (PowerShell):**
```powershell
docker cp ".\workflows\support-ticket-ai-demo.json" "n8n:/home/node/.n8n/import/"
docker exec n8n n8n import:workflow --input=/home/node/.n8n/import/support-ticket-ai-demo.json
```

Nach dem Import in n8n die Seite neu laden - der Workflow "Support Ticket Demo" erscheint dann in der Workflow-Liste.

### 4. AI-Secret hinterlegen

Den importierten Workflow öffnen und auf den Node **"Anthropic Chat Model1"** klicken. Dort eine neue Credential ("Anthropic account") mit einem eigenen [Anthropic API-Key](https://console.anthropic.com/settings/keys) anlegen und speichern.

> Ohne gültigen API-Key kann der Workflow das Ticket nicht klassifizieren.

### 5. Webhook auf "Listen for test event" stellen

Im Workflow-Editor auf den **Webhook**-Node klicken und **"Listen for test event"** auswählen. Der Webhook ist dann unter der Test-URL (`/webhook-test/support-ticket`) erreichbar, bis das nächste Mal ein Ticket eingeht.

### 6. Support-Ticket-Formular ausfüllen und absenden

Webapp unter [http://localhost:8080/ticket/new](http://localhost:8080/ticket/new) öffnen, Formular ausfüllen (z. B. Name, Beschreibung) und absenden.

> **Hinweis:** Vor jedem weiteren Test muss in n8n erneut "Listen for test event" aktiviert werden.

### 7. Ergebnis in Gotify prüfen

Gotify unter [http://localhost:8088](http://localhost:8088) öffnen und mit `admin` / `admin` einloggen. Wenige Sekunden nach dem Absenden des Tickets sollte eine Push-Benachrichtigung mit der von der AI ermittelten Priorität, Begründung sowie den Ticket-Daten erscheinen.

Zur Kontrolle kann zusätzlich in n8n unter **Executions** der Ablauf des Workflows (inkl. AI-Klassifizierung) nachvollzogen werden.

---

# n8n Secrets
Die n8n Secrets für AI API Keys müssen vor Ausführung des Workflows eingegeben werden, da die API Keys nicht mit exportiert werden

# Docker Stack starten
```
docker compose up -d
```

# Docker Stack stoppen
```
docker compose down
```

# Erreichbarkeit

| Dienst | URL |
| --- | --- |
| n8n | http://localhost:5678 |
| Webapp (Support-Ticket-Formular) | http://localhost:8080 |
| Gotify | http://localhost:8088/ |

Gotify Username: admin
Gotify Passwort: admin

# Workflow exportieren

Neue oder geänderte Workflows aus dem laufenden Container exportieren:

**Linux / macOS (bash):**
```bash
docker exec n8n n8n export:workflow --all --separate '--output=/home/node/.n8n/export/'
docker cp "n8n:/home/node/.n8n/export/." "./workflows/"
```

**Windows (PowerShell):**
```powershell
docker exec n8n n8n export:workflow --all --separate '--output=/home/node/.n8n/export/'
docker cp "n8n:/home/node/.n8n/export/." ".\workflows\"
```
---

# Workflow importieren

**Linux / macOS (bash):**
```bash
docker cp "./workflows/." "n8n:/home/node/.n8n/import/"
docker exec n8n n8n import:workflow '--separate' '--input=/home/node/.n8n/import/'
```

**Windows (PowerShell):**
```powershell
docker cp ".\workflows\." "n8n:/home/node/.n8n/import/"
docker exec n8n n8n import:workflow '--separate' '--input=/home/node/.n8n/import/'
```

> **Hinweis:** Credentials (API-Keys etc.) sind nicht in den JSON-Dateien enthalten und müssen nach einem Import manuell in n8n neu verknüpft werden.
