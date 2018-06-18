#!/bin/sh

echo Copying git pre commit hook...
cp scripts/git-hooks/pre-commit .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit
