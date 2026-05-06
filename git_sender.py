import subprocess
import sys
import os

def run_git_command(command, ignore_errors=False):
    try:
        subprocess.run(command, check=True, shell=True)
    except subprocess.CalledProcessError as e:
        if not ignore_errors:
            print(f"\n❌ Error while running: {command}")
            print(f"Details: {e}")
            sys.exit(1)

def ensure_gitignore():
    """Ensure .env is ignored if it exists."""
    if os.path.exists(".env"):
        print("🔒 .env file detected. Securing it...")

        gitignore_exists = os.path.exists(".gitignore")

        if not gitignore_exists:
            with open(".gitignore", "w") as f:
                f.write(".env\n")
            print("✅ Created .gitignore and added .env")
        else:
            with open(".gitignore", "r") as f:
                content = f.read()

            if ".env" not in content:
                with open(".gitignore", "a") as f:
                    f.write("\n.env\n")
                print("✅ Added .env to existing .gitignore")
            else:
                print("✅ .env already secured in .gitignore")

        # Remove .env from tracking if it was already added before
        run_git_command("git rm --cached .env", ignore_errors=True)

def main():
    print("🚀 HemLex Auto-Git Pusher 🚀\n")

    repo_url = "https://github.com/hemkhatri/herbal_website"
    if not repo_url:
        print("❌ No repo URL provided.")
        sys.exit(1)

    commit_msg = input("Commit message (Enter for default): ").strip()
    if not commit_msg:
        commit_msg = "Auto-commit: grinding on the code"

    print("\n🔥 Running git workflow...")

    run_git_command("git init")

    # 🔐 Critical step
    ensure_gitignore()

    run_git_command("git add .")
    run_git_command(f'git commit -m "{commit_msg}"', ignore_errors=True)

    run_git_command("git branch -M main")
    run_git_command("git remote remove origin", ignore_errors=True)
    run_git_command(f"git remote add origin {repo_url}")

    print("\n⏳ Pushing to GitHub...")
    run_git_command("git push -u origin main")

    print("\n✅ Done. Code is live (and secrets are safe).")

if __name__ == "__main__":
    main()