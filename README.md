# Influx WP Boostrap

This is the base repository for `content` used in common WordPress projects.

## Prerequisites

This setup works well with [Chassis](https://github.com/Chassis/Chassis).

## Using

```bash
# Clone this repo
git clone https://github.com/influx-inc/WordPress-Envato-Purchase-Installer.git content

cd content

# List purchased items
./envato list
./envato list plugins
./envato list themes

# Find an item
./envato find <item-name>

# Install an item by downloading it and unzipping it to either content/themes or content/plugins
./envato install <item-id>
```
