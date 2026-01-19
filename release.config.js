module.exports = {
  branches: [
    'main',
    { name: 'next', prerelease: true },
    { name: 'beta', prerelease: true },
  ],
  repositoryUrl: 'https://github.com/relaywarden/relaywarden-php-sdk',
  tagFormat: 'v${version}',
  plugins: [
    '@semantic-release/commit-analyzer',
    '@semantic-release/release-notes-generator',
    [
      '@semantic-release/changelog',
      {
        changelogFile: 'CHANGELOG.md',
      },
    ],
    [
      '@semantic-release/exec',
      {
        prepareCmd: 'node -e "const fs=require(\'fs\');const pkg=JSON.parse(fs.readFileSync(\'composer.json\'));pkg.version=\'${nextRelease.version}\';fs.writeFileSync(\'composer.json\',JSON.stringify(pkg,null,2)+\'\\n\')"',
      },
    ],
    [
      '@semantic-release/github',
      {
        assets: [
          { path: 'CHANGELOG.md', label: 'Changelog' },
        ],
      },
    ],
    [
      '@semantic-release/git',
      {
        assets: ['CHANGELOG.md', 'composer.json'],
        message: 'chore(release): ${nextRelease.version} [skip ci]\n\n${nextRelease.notes}',
      },
    ],
  ],
};
