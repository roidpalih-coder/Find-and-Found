import sys
import subprocess

try:
    import fitz
    import pandas as pd
    import tabulate
except ImportError:
    subprocess.check_call([sys.executable, '-m', 'pip', 'install', 'pymupdf', 'pandas', 'openpyxl', 'tabulate', '--quiet'])
    import fitz
    import pandas as pd

def pdf_to_md(pdf_path, md_path):
    doc = fitz.open(pdf_path)
    text = ""
    for page in doc:
        text += page.get_text("text") + "\n\n"
    with open(md_path, 'w', encoding='utf-8') as f:
        f.write(text)

def excel_to_md(xlsx_path, md_path):
    df = pd.read_excel(xlsx_path)
    md_text = df.to_markdown(index=False)
    with open(md_path, 'w', encoding='utf-8') as f:
        f.write(md_text)

try:
    pdf_to_md(r'c:\agit\Find&Found\Find & Found.pdf', r'c:\agit\Find&Found\Find_and_Found.md')
    print("Extracted Find & Found.pdf")
except Exception as e:
    print(f"Error Find & Found.pdf: {e}")

try:
    pdf_to_md(r'c:\agit\Find&Found\Find&Found (Jawaban) - Form Responses 1.pdf', r'c:\agit\Find&Found\Find_and_Found_Jawaban.md')
    print("Extracted Jawaban PDF")
except Exception as e:
    print(f"Error Jawaban PDF: {e}")

try:
    excel_to_md(r'c:\agit\Find&Found\Find&Found (Jawaban).xlsx', r'c:\agit\Find&Found\Find_and_Found_Jawaban_Excel.md')
    print("Extracted Jawaban Excel")
except Exception as e:
    print(f"Error Jawaban Excel: {e}")

print("Conversion complete.")
