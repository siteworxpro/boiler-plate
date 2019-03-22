VERSION='11.11.0'

wget https://nodejs.org/dist/v${VERSION}/node-v${VERSION}-linux-x64.tar.xz
curl -O https://nodejs.org/dist/v${VERSION}/SHASUMS256.txt
grep node-v${VERSION}-linux-x64.tar.xz SHASUMS256.txt | sha256sum -c -

tar -xvf node-v${VERSION}-linux-x64.tar.xz

mv node-v${VERSION}-linux-x64 /opt/node-v${VERSION}-linux-x64

ln -s /opt/node-v${VERSION}-linux-x64/bin/node /usr/bin/node
ln -s /opt/node-v${VERSION}-linux-x64/bin/npm /usr/bin/npm
ln -s /opt/node-v${VERSION}-linux-x64/bin/npx /usr/bin/npx
