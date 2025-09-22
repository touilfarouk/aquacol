@echo off
echo Generating PDF...

:: Create a temporary file without emojis
powershell -Command "(Get-Content form_development_guide.md) -replace '[^\u0000-\u007F]', '' | Set-Content form_development_guide_no_emoji.md"

:: Generate PDF using pandoc
pandoc ^
  --pdf-engine=xelatex ^
  -V mainfont="Arial" ^
  -V geometry:margin=1in ^
  -V urlcolor=blue ^
  -V linkcolor=blue ^
  -V toccolor=blue ^
  -V colorlinks=true ^
  -V documentclass=article ^
  -V papersize=a4 ^
  -V fontsize=12pt ^
  -f markdown+smart ^
  --toc ^
  --toc-depth=2 ^
  -o form_development_guide.pdf ^
  form_development_guide_no_emoji.md

del form_development_guide_no_emoji.md

echo.
echo PDF generated successfully: form_development_guide.pdf
pause
