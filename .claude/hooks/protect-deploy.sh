#!/usr/bin/env bash
# Bloque toute modification par Claude des fichiers de déploiement / serveur.
# Ces fichiers sont propres à la production (PlanetHoster) : les changer
# casserait le déploiement. Hook PreToolUse (Edit/Write/MultiEdit/NotebookEdit/Bash).
input=$(cat)
targets='(deploy\.php|\.htaccess|\.github/workflows/[^"[:space:]]*|scripts/[^"[:space:]]*|config/[^"[:space:]]*)'
protected="(^|/)${targets}"
in_cmd="(^|[/[:space:]\"'=])${targets}"

# Outils d'édition : chemin du fichier visé.
path=$(printf '%s' "$input" | grep -oE '"(file_path|notebook_path)"[[:space:]]*:[[:space:]]*"[^"]*"' | head -1 | sed -E 's/.*:[[:space:]]*"([^"]*)"$/\1/')
if [ -n "$path" ] && printf '%s' "$path" | grep -qE "${protected}\$"; then
  echo "BLOQUÉ : « $path » est un fichier de déploiement protégé (voir CLAUDE.md). Ne pas le modifier." >&2
  exit 2
fi

# Commandes shell qui écriraient dans ces fichiers (redirection, sed -i, rm, mv, cp, tee…).
cmd=$(printf '%s' "$input" | grep -oE '"command"[[:space:]]*:[[:space:]]*"([^"\\]|\\.)*"' | head -1)
if [ -n "$cmd" ] && printf '%s' "$cmd" | grep -qE "(>|sed[[:space:]]+-i|perl[[:space:]]+-[a-z]*i|\btee\b|\brm\b|\bmv\b|\bcp\b|\btruncate\b|git[[:space:]]+(checkout|restore|rm|mv))[^|;&]*${in_cmd}"; then
  echo "BLOQUÉ : cette commande modifierait un fichier de déploiement protégé (deploy.php, .htaccess, .github/workflows, scripts/, config/). Voir CLAUDE.md." >&2
  exit 2
fi
exit 0
