#!/bin/sh
set -e

# Spustí Chrome v headless módu na pozadí (pro MCP / Lighthouse / Chrome DevTools)
google-chrome-stable \
  --headless=new \
  --disable-gpu \
  --no-sandbox \
  --remote-debugging-address=0.0.0.0 \
  --remote-debugging-port=9222 \
  > /tmp/chrome.log 2>&1 &

exec "$@"