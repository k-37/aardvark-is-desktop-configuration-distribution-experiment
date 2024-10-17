# Introduction

This repository is not about code, although there is some unpolished code included 🙃, or a particular GNU/Linux distribution it is here to present a workflow proposal for initial desktop configuration similar to [dotfiles](https://dotfiles.github.io/).

In GNU/Linux world popular activity is *distro hopping*:

> The act of frequently changing the operating system of one's computer to a different distribution of Linux.
- https://en.wiktionary.org

Distribution is basically a collection of packages (programs) with someone's particular configuration of said packages, [as explained in the video](https://www.youtube.com/watch?v=DJ_4hfuidG0).

For desktop users there is a merit in trying different configuration of the desktop environment. But, by doing *distro hopping* we don't know what is secret spice that makes desktop of particular distribution special and we can not easily adapt it to our needs and share it with the community. Why not have distribution of desktop configuration which will give us ability to see the differences?

Aardvark's not 🐜!

# Requirements

On Debian/Ubuntu and similar:

    sudo apt-get install php-cli

# Usage

After cloning current repository in the root of the project execute:

    php ./bin/build for_distribution=debian12-with-kde system_type=prime

or:

    php ./bin/build for_distribution=debian12-with-kde system_type=spare

or

    php ./bin/build for_distribution=arch-linux-without-desktop-environment system_type=spare

Commands will take contents of `./configuration_sources` directory and generate final desktop configuration in `./target/<SPECIFIC_GENERATED_TARGET>` folder, contents of which then can be copied to user's `$HOME` directory.

> [!WARNING]
> I'm just explaining the concept here, don't overwrite files in your `$HOME` directory. I usually copy final configuration resources over SSH to user's empty `$HOME` folder or from another account with `sudo` privileges. At the moment, it is intended to be used only for initial configuration. Fresh virtual machine is recommended option to try it out.

With argument `for_distribution=debian12-with-kde` we instructed commands to take every folder in `./configuration_sources` containing `debian12-with-kde.json` and use that folder when generating final desktop configuration in `./target`. If we need to generate unique configuration for another distribution or for the new version of our distribution then we just create `my-beloved-configuration-distribution.json` in every `./configuration_sources/<PACKAGE_NAME>` folder we want see included in the final output and execute the command with `for_distribution=my-beloved-configuration-distribution`, note missing `.json`.

`<CONFIGURATION_DISTRIBUTION_NAME>.json` supports these options:

- `version` must be defined. It is used to *link* `<CONFIGURATION_DISTRIBUTION_NAME>.json` with sub-folders in the same folder, it is typically used when we want to use different configuration for the new version of the program.
- `manual_configuration_folder_name` is optional. It defines name for generated configuration in `./target/<SPECIFIC_GENERATED_TARGET>/Desktop/manual_configuration/<IT_GOES_HERE>`. For a desktop environment some configuration must be done manually, idea is to have special folder where user can follow simple steps to configure few options after first login.
- `templates` is optional. It is best to take a look at provided generated examples in `./target` directory and compare them with all files under `./configuration_sources/less/`. Template files **must have** `.tpl` extension.

Every desktop program on your system can be configured in the same way, `less` and [`KDE Plasma`](https://kde.org/plasma-desktop/) are used here mainly for example purposes. Or are they? In my humble opinion [KDE Plasma is THE GOAT 🐐](https://www.youtube.com/watch?v=Gyv-B1GX4K4).

Argument `system_type=<prime|spare>` used in commands allows us to have configuration variation based on the target system type. Currently there are two supported types `prime` and `spare` 🛞, new can easily be added by editing one line in `build` script, just search where is `prime` mentioned. There is a special folder `./configuration_sources/<PACKAGE_NAME>/<VERSION>/common` which will be included, if it exists, for every system type for given package and version.

When in doubt take a look at provided, already generated, examples in `./target` and compare them with `./configuration_sources`.

### Show debug info

    DEBUG=1 ./bin/build for_distribution=debian12-with-kde system_type=spare

# FAQ

### Why not use Ansible, SaltStack, Progress Chef or Puppet instead?

They are all fine projects but are also huge dependency for simple use cases.

### What are some of the lightweight and opinionated alternatives to this project?

- [LARBS: efficient shell script that will install a fully-featured tiling window manager-based system on any Arch or Artix Linux-based system](https://larbs.xyz/)
- [Omakub: opinionated Ubuntu Setup](https://omakub.org/)

### Why PHP?

[Some say PHP is the best](https://tomasvotruba.com/blog/php-is-the-best-choice-for-long-term-business). 🐘
