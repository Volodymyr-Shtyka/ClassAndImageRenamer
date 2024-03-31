# ClassAndImageRenamer

This PHP script automates the process of renaming class names and image files across your project files, ensuring
consistency and uniformity. It starts by targeting CSS files to gather and update class names, then proceeds to update
these names, as well as image file references, in HTML and JavaScript files.

## Purpose

`ClassAndImageRenamer` is intended to assist developers in refactoring and maintaining large projects by automating the
tedious task of renaming classes and images. It helps in preventing manual errors and saves time, especially in large
projects where consistency in naming conventions is crucial.

## Precautions Before Use

Before running this script:

1. **Backup Your Project**: Always ensure you have a complete backup of your project. This script modifies files
   directly, and having a backup ensures you can revert to the original state if needed.

2. **Remove External Libraries**: Temporarily remove or exclude external CSS and JS libraries (like Bootstrap, jQuery,
   etc.) from your project directory. This prevents unintended renaming within these libraries, which could lead to
   broken functionalities.

3. **Test in a Controlled Environment**: Test the script in a development or staging environment before applying it to
   your live project. Ensure that the project operates as expected after the script execution.

## How to Use

1. Clone this repository to your local machine or download the `AutoRenameTool.php` file directly.

2. Place `AutoRenameTool.php` in the root directory of your project.

3. Run the script from your terminal:

```php
php AutoRenameTool.php;
```

4. Review the changes, test your project thoroughly, and restore any external libraries you may have removed.

## Contribution

Contributions to `ClassAndImageRenamer` are welcome! Whether it's adding new features, improving existing ones, or
reporting issues, your input is highly valued. Please feel free to fork this repository, make changes, and submit pull
requests.

## About the Author

**Volodymyr Shtyka** is a passionate web developer with a keen interest in creating tools that streamline
development processes and enhance productivity. With extensive experience in PHP, JavaScript, and web development, 
Volodymyr seeks to contribute to the developer community by sharing innovative solutions and best practices.

Volodymyr's commitment to continuous learning and professional growth is reflected in the quality and utility of his
projects. Through `ClassAndImageRenamer`, Volodymyr aims to address common challenges faced by developers, offering a
practical tool to simplify project maintenance and refactoring efforts.

### Connect with Volodymyr

- **GitHub**: [Volodymyr's GitHub](https://github.com/Volodymyr-Shtyka)
- **LinkedIn**: [Volodymyr's LinkedIn](https://www.linkedin.com/in/vshtyka)

Volodymyr welcomes feedback, contributions, and the opportunity to collaborate on exciting projects. Feel free to reach
out to him through any of the above platforms.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
