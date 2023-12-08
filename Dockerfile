FROM php:7.2-apache

# Install Java dependency
RUN apt-get -y update
RUN mkdir -p /usr/share/man/man1/
RUN apt-get install -y openjdk-11-jre-headless \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy code from current directory
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Set permissions for user
RUN chown -R www-data:www-data /var/www

# Expose port
EXPOSE 80

# Copy the entrypoint script into the image
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Use the entrypoint script to start Apache
ENTRYPOINT ["entrypoint.sh"]