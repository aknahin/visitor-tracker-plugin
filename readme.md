# Visitor Tracker Plugin

Visitor Tracker is a WordPress plugin that tracks visitors and sends an email after each session ends. This plugin also includes a settings page where administrators can configure email addresses, the subject line for the emails, and view a log of all email sends.

## Features

- Track visitor IP, session start and end times, and pages visited.
- Send an email with visitor details at the end of each session.
- Configurable email addresses and subject line through the settings page.
- Log of email send times and dates in the settings page.

## Installation

1. Download the `visitor-tracker-plugin` folder.
2. Upload the folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Go to 'Settings' -> 'Visitor Tracker' to configure the plugin.

## Usage

1. **Email Addresses**: Enter the email addresses where you want to receive visitor details. Separate multiple emails with a new line.
2. **Email Subject**: Customize the subject line for the emails sent. The default subject is "New visitor on your website".
3. **Email Sending Log**: View the log of all email sends with the date and time.

## Development

Feel free to contribute to the development of this plugin. Follow the steps below to get started:

### Setup

1. Clone the repository:
    ```bash
    git clone https://github.com/yourusername/visitor-tracker-plugin.git
    ```
2. Navigate to the plugin directory:
    ```bash
    cd visitor-tracker-plugin
    ```
3. Install the plugin in your WordPress setup as described in the installation steps.

### Contributing

1. Fork the repository.
2. Create a new branch for your feature or bugfix:
    ```bash
    git checkout -b feature-name
    ```
3. Make your changes and commit them:
    ```bash
    git commit -m "Description of your changes"
    ```
4. Push to the branch:
    ```bash
    git push origin feature-name
    ```
5. Create a pull request.

## Author

**AK Nahin**
- [Website](https://aknahin.com)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
