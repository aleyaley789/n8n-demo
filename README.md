# Setup

Vor dem ersten Start müssen die Umgebungsvariablen aus den `.env.dist`-Vorlagen angelegt werden:

```
cp .env.dist .env
cp webapp/.env.dist webapp/.env
```

Die Default-Werte sind für den lokalen Betrieb ausreichend, können bei Bedarf aber angepasst werden.

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

# Workflow exportieren

Neue oder geänderte Workflows aus dem laufenden Container exportieren:

```powershell
# Alle Workflows auf einmal exportieren
docker exec n8n n8n export:workflow --all --separate '--output=/home/node/.n8n/export/'
docker cp "n8n:/home/node/.n8n/export/." ".\workflows\"
```
---

# Workflow importieren

```powershell
docker cp ".\workflows\." "n8n:/home/node/.n8n/import/"
docker exec n8n n8n import:workflow '--separate' '--input=/home/node/.n8n/import/'
```

> **Hinweis:** Credentials (API-Keys etc.) sind nicht in den JSON-Dateien enthalten und müssen nach einem Import manuell in n8n neu verknüpft werden.
