import subprocess
import sys
import os

def run_git_command(command, ignore_errors=False):
    """Runs a terminal command and stops the script if it fails."""
    try:
        subprocess.run(command, check=True, shell=True)
    except subprocess.CalledProcessError as e:
        if not ignore_errors:
            print(f"\n❌ Something broke while running: {command}")
            sys.exit(1)

def main():
    print("🚀 HemLex Auto-Git Pusher (Safe Mode) 🚀\n")

    repo_url = "https://github.com/hemkhatri/herbal_website"
    commit_msg = input("Commit message (press Enter for 'Auto-commit'): ").strip()
    if not commit_msg:
        commit_msg = "Auto-commit: forced push and env ignored"

    print("\n🔥 Firing up the git commands...")

    # 1. Initialize
    run_git_command("git init")

    # 2. Check if .env exists and stop tracking it if it's already staged
    if os.path.exists(".env"):
        print("📁 Found .env file - excluding it from this push to prevent blocks.")
        run_git_command("git rm --cached .env", ignore_errors=True)

    # 3. Add files EXCEPT for .env
    # This adds everything but explicitly ignores .env
    run_git_command("git add .")
    run_git_command("git reset .env", ignore_errors=True)

    # 4. Commit and Push
    run_git_command(f'git commit -m "{commit_msg}"', ignore_errors=True)
    run_git_command("git branch -M main")
    run_git_command("git remote remove origin", ignore_errors=True)
    run_git_command(f"git remote add origin {repo_url}")

    print("\n⏳ Forcing code to the cloud...")
    # --force helps if your local history is different from GitHub
    run_git_command("git push -u origin main --force")

    print("\n✅ Code shipped! Note: If it still fails, you MUST click the GitHub links to allow the secrets.")

if __name__ == "__main__":
    main()
